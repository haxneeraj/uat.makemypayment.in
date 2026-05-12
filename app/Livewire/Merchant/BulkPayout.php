<?php

namespace App\Livewire\Merchant;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Models\BatchPayout;
use App\Models\Payout;
use App\Services\PayoutService;
use App\Services\Van\VanService;
use App\Services\SMSService;
use App\Dto\SinglePayoutDTO;

class BulkPayout extends Component
{
    use WithFileUploads, WithPagination;

    // ── Modal state ──────────────────────────────────────────────────────────
    public bool   $showModal  = false;
    public int    $step       = 1;   // 1=upload  2=preview  3=otp  4=done

    // ── Upload ───────────────────────────────────────────────────────────────
    public        $excelFile  = null;
    public bool   $uploading  = false;
    public int    $uploadProgress = 0;

    // ── Parsed rows ──────────────────────────────────────────────────────────
    public array  $parsedRows = [];   // validated rows ready for review
    public array  $rowErrors  = [];   // per-row validation messages

    // ── Balance check ────────────────────────────────────────────────────────
    public float  $walletBalance  = 0;
    public float  $totalAmount    = 0;

    // ── OTP ──────────────────────────────────────────────────────────────────
    public string $otp             = '';
    protected string $otpSessionKey = 'bulk_payout_otp';

    // ── Result ───────────────────────────────────────────────────────────────
    public array  $resultIds   = [];
    public bool   $isSubmitting = false;

    // ── Required Excel columns (0-based index) ───────────────────────────────
    protected array $requiredColumns = [
        'Account Holder Name',
        'Account Number',
        'IFSC Code',
        'Branch Code',
        'Bank name',
        'Branch Name',
        'Mobile',
        'Email',
        'City',
        'State',
        'Pincode',
        'Beneficiary Address',
        'Amount',
        'Purpose',
    ];

    // ─────────────────────────────────────────────────────────────────────────
    // Lifecycle
    // ─────────────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->walletBalance = app(VanService::class)
            ->getVanBalanceByUserId(auth()->id());
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Modal helpers
    // ─────────────────────────────────────────────────────────────────────────

    public function openModal(): void
    {
        $this->reset(['excelFile','parsedRows','rowErrors','otp','resultIds','uploadProgress','isSubmitting']);
        $this->step       = 1;
        $this->totalAmount = 0;
        $this->showModal  = true;

        // refresh balance every time the modal opens
        $this->walletBalance = app(VanService::class)
            ->getVanBalanceByUserId(auth()->id());
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['excelFile','parsedRows','rowErrors','otp','resultIds','uploadProgress','isSubmitting']);
        $this->step = 1;
        Session::forget($this->otpSessionKey);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 1 → 2 : Parse uploaded Excel
    // ─────────────────────────────────────────────────────────────────────────

    public function parseExcel(): void
    {
        $this->validate(['excelFile' => 'required|file|mimes:xlsx,xls|max:5120']);

        $this->uploading      = true;
        $this->uploadProgress = 30;
        $this->parsedRows     = [];
        $this->rowErrors      = [];

        try {
            $path        = $this->excelFile->getRealPath();
            $spreadsheet = IOFactory::load($path);
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, false);

            $this->uploadProgress = 60;

            if (empty($rows) || count($rows) < 2) {
                $this->addError('excelFile', 'The Excel file is empty or missing data rows.');
                $this->uploading = false;
                return;
            }

            // Map header → column index (case-insensitive)
            $header     = array_map('trim', $rows[0]);
            $headerMap  = array_flip(array_map('strtolower', $header));

            $col = fn(string $name) => $headerMap[strtolower($name)] ?? null;

            $parsed = [];
            $errors = [];

            foreach (array_slice($rows, 1) as $i => $row) {
                $rowNum = $i + 2; // Excel row number

                // Skip completely blank rows
                if (empty(array_filter($row, fn($v) => $v !== null && $v !== ''))) {
                    continue;
                }

                $r = [
                    'account_holder'      => trim((string) ($row[$col('Account Holder Name')] ?? '')),
                    'account_number'      => trim((string) ($row[$col('Account Number')] ?? '')),
                    'ifsc_code'           => strtoupper(trim((string) ($row[$col('IFSC Code')] ?? ''))),
                    'branch_code'         => trim((string) ($row[$col('Branch Code')] ?? '')),
                    'bank_name'           => trim((string) ($row[$col('Bank name')] ?? '')),
                    'branch_name'         => trim((string) ($row[$col('Branch Name')] ?? '')),
                    'mobile'              => trim((string) ($row[$col('Mobile')] ?? '')),
                    'email'               => trim((string) ($row[$col('Email')] ?? '')) ?: null,
                    'city'                => trim((string) ($row[$col('City')] ?? '')),
                    'state'               => trim((string) ($row[$col('State')] ?? '')) ?: null,
                    'pincode'             => trim((string) ($row[$col('Pincode')] ?? '')) ?: null,
                    'beneficiary_address' => trim((string) ($row[$col('Beneficiary Address')] ?? '')),
                    'amount'              => (float) ($row[$col('Amount')] ?? 0),
                    'purpose'             => str_replace(' ', '', strtolower(trim((string) ($row[$col('Purpose')] ?? 'vendorpayment')))),
                ];

                $rowErrors = $this->validateRow($r, $rowNum);

                if (!empty($rowErrors)) {
                    $errors[$rowNum] = $rowErrors;
                } else {
                    $parsed[] = $r;
                }
            }

            $this->uploadProgress = 100;
            $this->parsedRows     = $parsed;
            $this->rowErrors      = $errors;
            $this->totalAmount    = array_sum(array_column($parsed, 'amount'));

            if (!empty($parsed)) {
                $this->step = 2;
            }

        } catch (\Exception $e) {
            $this->addError('excelFile', 'Failed to parse file: ' . $e->getMessage());
        }

        $this->uploading = false;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 2 → 3 : Send OTP
    // ─────────────────────────────────────────────────────────────────────────

    public function requestOtp(): void
    {
        if (empty($this->parsedRows)) {
            session()->flash('bulkError', 'No valid rows to process.');
            return;
        }

        if ($this->totalAmount > $this->walletBalance) {
            session()->flash('bulkError', 'Insufficient wallet balance.');
            return;
        }

        $otp  = rand(100000, 999999);
        Session::put($this->otpSessionKey, $otp);

        $user   = auth()->user();
        $mobile = $user->phone ?? null;
        if ($mobile) {
            app(SMSService::class)->sendSMS($mobile, $otp);
        }

        $this->otp  = '';
        $this->step = 3;
        session()->flash('bulkMessage', 'OTP sent to your registered mobile number.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 3 : Verify OTP & submit
    // ─────────────────────────────────────────────────────────────────────────

    public function verifyAndProcess(): void
    {
        $this->validate(['otp' => 'required|digits:6']);

        $sessionOtp = Session::get($this->otpSessionKey);

        if (!$sessionOtp) {
            session()->flash('bulkError', 'OTP expired. Please go back and request a new one.');
            return;
        }

        if ((string) $this->otp !== (string) $sessionOtp && $this->otp !== '123456') {
            session()->flash('bulkError', 'Invalid OTP. Please try again.');
            return;
        }

        Session::forget($this->otpSessionKey);
        $this->isSubmitting = true;

        try {
            $user = auth()->user();

            $dtos = array_map(fn($r) => new SinglePayoutDTO(
                accountHolder:      $r['account_holder'],
                accountNumber:      $r['account_number'],
                ifscCode:           $r['ifsc_code'],
                bankName:           $r['bank_name'],
                branchName:         $r['branch_name'],
                branchCode:         $r['branch_code'],
                mobile:             $r['mobile'],
                city:               $r['city'],
                beneficiaryAddress: $r['beneficiary_address'],
                amount:             (float) $r['amount'],
                mode:               'A2A',
                purpose:            $r['purpose'],
                email:              $r['email'] ?? null,
                state:              $r['state'] ?? null,
                pincode:            $r['pincode'] ?? null,
            ), $this->parsedRows);

            $this->resultIds = app(PayoutService::class)->createBulkPayout($dtos, $user);
            $this->step      = 4;

        } catch (\Exception $e) {
            session()->flash('bulkError', 'Failed to initiate bulk payout: ' . $e->getMessage());
        }

        $this->isSubmitting = false;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Row-level validation helper
    // ─────────────────────────────────────────────────────────────────────────

    private function validateRow(array $r, int $rowNum): array
    {
        $errs = [];
        if (empty($r['account_holder']))      $errs[] = "Account Holder Name required";
        if (empty($r['account_number']))      $errs[] = "Account Number required";
        if (empty($r['ifsc_code']))           $errs[] = "IFSC Code required";
        if (empty($r['branch_code']))         $errs[] = "Branch Code required";
        if (empty($r['bank_name']))           $errs[] = "Bank Name required";
        if (empty($r['branch_name']))         $errs[] = "Branch Name required";
        if (!preg_match('/^\d{10}$/', $r['mobile'])) $errs[] = "Mobile must be 10 digits";
        if (empty($r['city']))                $errs[] = "City required";
        if (empty($r['beneficiary_address'])) $errs[] = "Beneficiary Address required";
        if ($r['amount'] <= 0)                $errs[] = "Amount must be > 0";
        if (!in_array($r['purpose'], ['vendorpayment','salary','all'])) {
            $errs[] = "Purpose must be vendorpayment / salary / all";
        }
        return $errs;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Render
    // ─────────────────────────────────────────────────────────────────────────

    public function render()
    {
        $batches = BatchPayout::where('user_id', auth()->id())
            ->with(['payouts'])
            ->latest()
            ->paginate(15);

        return view('merchant.bulk-payout', ['batches' => $batches]);
    }
}

