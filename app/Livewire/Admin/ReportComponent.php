<?php

namespace App\Livewire\Admin;

use App\Models\Deposit;
use App\Models\Payout;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ReportComponent extends Component
{
    use WithPagination;

    public $payoutSearchBy = 'reference';
    public $payoutSearch = '';
    public $payoutStatus = '';
    public $payoutDateFrom = '';
    public $payoutDateTo = '';
    public $payoutMerchantId = '';
    public $payoutPerPage = 15;

    public $depositSearchBy = 'reference';
    public $depositSearch = '';
    public $depositStatus = '';
    public $depositDateFrom = '';
    public $depositDateTo = '';
    public $depositMerchantId = '';
    public $depositPerPage = 15;

    public $entryType = 'payout';

    protected $queryString = [
        'payoutSearchBy' => ['except' => 'reference'],
        'payoutSearch' => ['except' => ''],
        'payoutStatus' => ['except' => ''],
        'payoutDateFrom' => ['except' => ''],
        'payoutDateTo' => ['except' => ''],
        'payoutMerchantId' => ['except' => ''],
        'payoutPerPage' => ['except' => 15],
        'depositSearchBy' => ['except' => 'reference'],
        'depositSearch' => ['except' => ''],
        'depositStatus' => ['except' => ''],
        'depositDateFrom' => ['except' => ''],
        'depositDateTo' => ['except' => ''],
        'depositMerchantId' => ['except' => ''],
        'depositPerPage' => ['except' => 15],
        'entryType' => ['except' => 'payout'],
    ];

    public function mount(): void
    {
        if (!in_array($this->entryType, ['payout', 'deposit'], true)) {
            $this->entryType = 'payout';
        }

        $today = now()->toDateString();

        if (blank($this->payoutDateFrom)) {
            $this->payoutDateFrom = $today;
        }

        if (blank($this->payoutDateTo)) {
            $this->payoutDateTo = $today;
        }

        if (blank($this->depositDateFrom)) {
            $this->depositDateFrom = $today;
        }

        if (blank($this->depositDateTo)) {
            $this->depositDateTo = $today;
        }
    }

    public function paginationView()
    {
        return 'components.custom-pagination';
    }

    public function updated($property): void
    {
        if (in_array($property, [
            'entryType',
            'payoutSearchBy',
            'payoutSearch',
            'payoutStatus',
            'payoutDateFrom',
            'payoutDateTo',
            'payoutMerchantId',
            'payoutPerPage',
            'depositSearchBy',
            'depositSearch',
            'depositStatus',
            'depositDateFrom',
            'depositDateTo',
            'depositMerchantId',
            'depositPerPage',
        ], true)) {
            $this->resetPage();
        }
    }

    public function setEntryType(string $type): void
    {
        if (! in_array($type, ['payout', 'deposit'], true)) {
            return;
        }

        if ($this->entryType === $type) {
            return;
        }

        $this->entryType = $type;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $today = now()->toDateString();

        if ($this->entryType === 'payout') {
            $this->payoutSearchBy = 'reference';
            $this->payoutSearch = '';
            $this->payoutStatus = '';
            $this->payoutDateFrom = $today;
            $this->payoutDateTo = $today;
            $this->payoutMerchantId = '';
            $this->payoutPerPage = 15;
        } else {
            $this->depositSearchBy = 'reference';
            $this->depositSearch = '';
            $this->depositStatus = '';
            $this->depositDateFrom = $today;
            $this->depositDateTo = $today;
            $this->depositMerchantId = '';
            $this->depositPerPage = 15;
        }

        $this->resetPage();
        $this->dispatch('reportFiltersCleared');
    }

    public function getHasActiveFiltersProperty(): bool
    {
        $today = now()->toDateString();

        if ($this->entryType === 'payout') {
            return $this->payoutSearch !== ''
                || $this->payoutStatus !== ''
                || $this->payoutMerchantId !== ''
                || $this->payoutSearchBy !== 'reference'
                || $this->payoutDateFrom !== $today
                || $this->payoutDateTo !== $today;
        }

        return $this->depositSearch !== ''
            || $this->depositStatus !== ''
            || $this->depositMerchantId !== ''
            || $this->depositSearchBy !== 'reference'
            || $this->depositDateFrom !== $today
            || $this->depositDateTo !== $today;
    }

    public function downloadCsv()
    {
        $rows = DB::query()
            ->fromSub($this->activeExportRowsQuery()->limit(5000), 'report_rows')
            ->orderByDesc('txn_at')
            ->get();

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Type',
                'Merchant ID',
                'Merchant Name',
                'Merchant Mobile',
                'Reference No',
                'Name',
                'Bank',
                'Account',
                'Amount',
                'Charges',
                'Total',
                'Status',
                'Date',
            ]);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    ucfirst($row->entry_type),
                    $row->merchant_id,
                    $row->merchant_name,
                    $row->merchant_mobile,
                    $row->reference_no,
                    $row->party_name,
                    $row->bank_name,
                    $row->account_no,
                    (float) $row->amount,
                    (float) $row->charges,
                    (float) $row->total_amount,
                    $row->source_status,
                    $row->txn_at,
                ]);
            }

            fclose($handle);
        }, 'admin-report-' . now()->format('Ymd-His') . '.csv');
    }

    public function downloadPdf()
    {
        $rows = DB::query()
            ->fromSub($this->activeExportRowsQuery()->limit(1000), 'report_rows')
            ->orderByDesc('txn_at')
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf', [
            'rows' => $rows,
            'generatedAt' => now(),
            'adminName' => auth()->user()->full_name,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'admin-report-' . now()->format('Ymd-His') . '.pdf');
    }

    private function payoutRowsQuery()
    {
        return Payout::query()
            ->leftJoin('users', 'users.id', '=', 'payouts.user_id')
            ->where('users.role', 'merchant')
            ->selectRaw(" 
                'payout' as entry_type,
                users.id as merchant_id,
                users.full_name as merchant_name,
                users.phone as merchant_mobile,
                payouts.transaction_id as reference_no,
                payouts.account_holder as party_name,
                payouts.bank_name,
                payouts.account_number as account_no,
                payouts.amount,
                COALESCE(payouts.fee, 0) as charges,
                COALESCE(payouts.total_amount, payouts.amount + COALESCE(payouts.fee, 0)) as total_amount,
                payouts.status as source_status,
                CASE WHEN payouts.status = 'success' THEN 'success' ELSE payouts.status END as normalized_status,
                payouts.initiated_at as txn_at
            ");
    }

    private function depositRowsQuery()
    {
        return Deposit::query()
            ->leftJoin('users', 'users.id', '=', 'deposits.user_id')
            ->where('users.role', 'merchant')
            ->selectRaw(" 
                'deposit' as entry_type,
                users.id as merchant_id,
                users.full_name as merchant_name,
                users.phone as merchant_mobile,
                deposits.alert_sequence_no as reference_no,
                deposits.remitter_name as party_name,
                deposits.remitter_bank as bank_name,
                COALESCE(deposits.virtual_account, deposits.remitter_account, deposits.account_number) as account_no,
                deposits.amount,
                0 as charges,
                deposits.amount as total_amount,
                deposits.processing_status as source_status,
                CASE WHEN deposits.processing_status = 'success' THEN 'success' ELSE deposits.processing_status END as normalized_status,
                deposits.transaction_date as txn_at
            ");
    }

    private function activeRowsQuery()
    {
        if ($this->entryType === 'payout') {
            $query = Payout::query()
                ->select([
                    'id',
                    'user_id',
                    'transaction_id',
                    'utr',
                    'account_holder',
                    'account_number',
                    'bank_name',
                    'ifsc_code',
                    'amount',
                    'fee',
                    'total_amount',
                    'status',
                    'mode',
                    'initiated_at',
                    'created_at',
                ])
                ->with([
                    'user:id,full_name,phone',
                    'refund:id,payout_id,amount,status',
                ]);

            $this->applyPayoutTableFilters($query);

            return $query->orderByDesc('initiated_at');
        }

        $query = Deposit::query()
            ->select([
                'id',
                'user_id',
                'alert_sequence_no',
                'virtual_account',
                'transaction_date',
                'amount',
                'transaction_description',
                'mnemonic_code',
                'processing_status',
            ])
            ->with([
                'user:id,full_name,phone',
            ]);

        $this->applyDepositTableFilters($query);

        return $query->orderByDesc('transaction_date');
    }

    private function activeExportRowsQuery()
    {
        if ($this->entryType === 'payout') {
            $query = $this->payoutRowsQuery();
            $this->applyPayoutExportFilters($query);

            return $query;
        }

        $query = $this->depositRowsQuery();
        $this->applyDepositExportFilters($query);

        return $query;
    }

    private function summaryRowsQuery()
    {
        return $this->activeRowsQuery();
    }

    public function render()
    {
        $perPage = $this->entryType === 'payout'
            ? (int) $this->payoutPerPage
            : (int) $this->depositPerPage;

        $reports = $this->activeRowsQuery()->paginate($perPage);

        if ($this->entryType === 'payout') {
            $query = Payout::query();
            $this->applyPayoutTableFilters($query);

            $summary = [
                'total_transactions' => (clone $query)->count(),
                'total_volume' => (float) (clone $query)->sum('total_amount'),
                'payout_volume' => (float) (clone $query)->sum('total_amount'),
                'inward_volume' => 0,
                'payout_transactions' => (clone $query)->count(),
                'inward_transactions' => 0,
                'success_transactions' => (clone $query)->where('status', 'success')->count(),
            ];
        } else {
            $query = Deposit::query();
            $this->applyDepositTableFilters($query);

            $summary = [
                'total_transactions' => (clone $query)->count(),
                'total_volume' => (float) (clone $query)->sum('amount'),
                'payout_volume' => 0,
                'inward_volume' => (float) (clone $query)->sum('amount'),
                'payout_transactions' => 0,
                'inward_transactions' => (clone $query)->count(),
                'success_transactions' => (clone $query)->where('processing_status', 'success')->count(),
            ];
        }

        $merchants = User::query()
            ->where('role', 'merchant')
            ->orderBy('full_name')
            ->get(['id', 'full_name', 'phone']);

        return view('admin.report-component')
        ->with([
            'reports' => $reports,
            'summary' => (object) $summary,
            'merchants' => $merchants,
            'successRate' => ($summary['total_transactions'] > 0)
                ? round(($summary['success_transactions'] / $summary['total_transactions']) * 100, 1)
                : 0,
        ])
        ->layout('layouts.admin')
        ->layoutData([
            'active' => 'reports',
            'pageTitle' => 'Reports',
            'metaTitle' => 'Reports - MMP Fintech',
            'metaDescription' => 'Reports',
        ]);
    }

    private function applyPayoutTableFilters($query): void
    {
        $search = trim((string) $this->payoutSearch);
        $today = now()->toDateString();
        $toDate = $this->payoutDateTo ?: $today;

        if ($search !== '') {
            if ($this->payoutSearchBy === 'merchant') {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('full_name', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%")
                        ->orWhere('id', 'like', "%$search%");
                });
            } else {
                $column = match ($this->payoutSearchBy) {
                    'name' => 'account_holder',
                    'bank' => 'bank_name',
                    'account' => 'account_number',
                    default => 'transaction_id',
                };

                $query->where($column, 'like', "%$search%");
            }
        }

        if ($this->payoutStatus !== '') {
            $query->where('status', $this->payoutStatus);
        }

        if ($this->payoutMerchantId !== '') {
            $query->where('user_id', $this->payoutMerchantId);
        }

        if ($this->payoutDateFrom !== '') {
            $query->whereBetween('created_at', [
                $this->payoutDateFrom . ' 00:00:00',
                $toDate . ' 23:59:59',
            ]);
        } else {
            $query->whereBetween('created_at', [
                $today . ' 00:00:00',
                $today . ' 23:59:59',
            ]);
        }
    }

    private function applyDepositTableFilters($query): void
    {
        $search = trim((string) $this->depositSearch);
        $today = now()->toDateString();
        $toDate = $this->depositDateTo ?: $today;

        if ($search !== '') {
            if ($this->depositSearchBy === 'merchant') {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('full_name', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%")
                        ->orWhere('id', 'like', "%$search%");
                });
            } else {
                if ($this->depositSearchBy === 'account') {
                    $query->whereRaw('COALESCE(virtual_account, remitter_account, account_number) like ?', ['%' . $search . '%']);
                } else {
                    $column = match ($this->depositSearchBy) {
                        'name' => 'remitter_name',
                        'bank' => 'remitter_bank',
                        default => 'alert_sequence_no',
                    };

                    $query->where($column, 'like', '%' . $search . '%');
                }
            }
        }

        if ($this->depositStatus !== '') {
            $query->where('processing_status', $this->depositStatus);
        }

        if ($this->depositMerchantId !== '') {
            $query->where('user_id', $this->depositMerchantId);
        }

        if ($this->depositDateFrom !== '') {
            $query->whereDate('transaction_date', '>=', $this->depositDateFrom)
                ->whereDate('transaction_date', '<=', $toDate);
        } else {
            $query->whereDate('transaction_date', $today);
        }
    }

    private function applyPayoutExportFilters($query): void
    {
        $search = trim((string) $this->payoutSearch);
        $today = now()->toDateString();
        $toDate = $this->payoutDateTo ?: $today;

        if ($search !== '') {
            if ($this->payoutSearchBy === 'merchant') {
                $query->where(function ($q) use ($search) {
                    $q->where('users.full_name', 'like', '%' . $search . '%')
                        ->orWhere('users.phone', 'like', '%' . $search . '%')
                        ->orWhere('users.id', 'like', '%' . $search . '%');
                });
            } else {
                $column = match ($this->payoutSearchBy) {
                    'name' => 'payouts.account_holder',
                    'bank' => 'payouts.bank_name',
                    'account' => 'payouts.account_number',
                    default => 'payouts.transaction_id',
                };

                $query->where($column, 'like', "%$search%");
            }
        }

        if ($this->payoutStatus !== '') {
            $query->where('payouts.status', $this->payoutStatus);
        }

        if ($this->payoutMerchantId !== '') {
            $query->where('users.id', $this->payoutMerchantId);
        }

        if ($this->payoutDateFrom !== '') {
            $query->whereBetween('payouts.created_at', [
                $this->payoutDateFrom . ' 00:00:00',
                $toDate . ' 23:59:59',
            ]);
        } else {
            $query->whereBetween('payouts.created_at', [
                $today . ' 00:00:00',
                $today . ' 23:59:59',
            ]);
        }
    }

    private function applyDepositExportFilters($query): void
    {
        $search = trim((string) $this->depositSearch);
        $today = now()->toDateString();
        $toDate = $this->depositDateTo ?: $today;

        if ($search !== '') {
            if ($this->depositSearchBy === 'merchant') {
                $query->where(function ($q) use ($search) {
                    $q->where('users.full_name', 'like', '%' . $search . '%')
                        ->orWhere('users.phone', 'like', '%' . $search . '%')
                        ->orWhere('users.id', 'like', '%' . $search . '%');
                });
            } else {
                if ($this->depositSearchBy === 'account') {
                    $query->whereRaw('COALESCE(deposits.virtual_account, deposits.remitter_account, deposits.account_number) like ?', ['%' . $search . '%']);
                } else {
                    $column = match ($this->depositSearchBy) {
                        'name' => 'deposits.remitter_name',
                        'bank' => 'deposits.remitter_bank',
                        default => 'deposits.alert_sequence_no',
                    };

                    $query->where($column, 'like', '%' . $search . '%');
                }
            }
        }

        if ($this->depositStatus !== '') {
            $query->where('deposits.processing_status', $this->depositStatus);
        }

        if ($this->depositMerchantId !== '') {
            $query->where('users.id', $this->depositMerchantId);
        }

        if ($this->depositDateFrom !== '') {
            $query->whereDate('deposits.transaction_date', '>=', $this->depositDateFrom)
                ->whereDate('deposits.transaction_date', '<=', $toDate);
        } else {
            $query->whereDate('deposits.transaction_date', $today);
        }
    }
}
