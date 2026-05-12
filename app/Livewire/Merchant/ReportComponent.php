<?php

namespace App\Livewire\Merchant;

use App\Models\Deposit;
use App\Models\Payout;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ReportComponent extends Component
{
    use WithPagination;

    public $searchBy = 'reference';
    public $search = '';
    public $status = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 15;
    public $entryType = 'payout';

    protected $queryString = [
        'searchBy' => ['except' => 'reference'],
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'perPage' => ['except' => 15],
        'entryType' => ['except' => 'payout'],
    ];

    public function paginationView()
    {
        return 'components.custom-pagination';
    }

    public function updatingSearchBy(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function updatingEntryType(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->searchBy = 'reference';
        $this->search = '';
        $this->status = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->perPage = 15;
        $this->entryType = 'payout';
        $this->resetPage();
        $this->dispatch('reportFiltersCleared');
    }

    public function downloadCsv()
    {
        $rows = $this->baseRowsQuery()->limit(5000)->get();

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Type',
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
        }, 'merchant-report-' . now()->format('Ymd-His') . '.csv');
    }

    public function downloadPdf()
    {
        $rows = $this->baseRowsQuery()->limit(1000)->get();

        $pdf = Pdf::loadView('merchant.reports.pdf', [
            'rows' => $rows,
            'generatedAt' => now(),
            'merchantName' => auth()->user()->name,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'merchant-report-' . now()->format('Ymd-His') . '.pdf');
    }

    private function baseRowsQuery()
    {
        $merchantId = auth()->id();

        $payoutQuery = Payout::query()
            ->where('user_id', $merchantId)
            ->selectRaw("
                'payout' as entry_type,
                transaction_id as reference_no,
                account_holder as party_name,
                bank_name,
                account_number as account_no,
                amount,
                COALESCE(fee, 0) as charges,
                COALESCE(total_amount, amount + COALESCE(fee, 0)) as total_amount,
                status as source_status,
                CASE WHEN status = 'success' THEN 'success' ELSE status END as normalized_status,
                initiated_at as txn_at
            ");

        $depositQuery = Deposit::query()
            ->where('user_id', $merchantId)
            ->selectRaw("
                'deposit' as entry_type,
                alert_sequence_no as reference_no,
                remitter_name as party_name,
                remitter_bank as bank_name,
                COALESCE(virtual_account, remitter_account, account_number) as account_no,
                amount,
                0 as charges,
                amount as total_amount,
                processing_status as source_status,
                CASE WHEN processing_status = 'success' THEN 'success' ELSE processing_status END as normalized_status,
                transaction_date as txn_at
            ");

        $search = trim($this->search);

        if ($search !== '') {
            $payoutColumn = match ($this->searchBy) {
                'name' => 'account_holder',
                'bank' => 'bank_name',
                'account' => 'account_number',
                default => 'transaction_id',
            };

            $payoutQuery->where($payoutColumn, 'like', '%' . $search . '%');

            if ($this->searchBy === 'account') {
                $depositQuery->whereRaw('COALESCE(virtual_account, remitter_account, account_number) like ?', ['%' . $search . '%']);
            } else {
                $depositColumn = match ($this->searchBy) {
                    'name' => 'remitter_name',
                    'bank' => 'remitter_bank',
                    default => 'alert_sequence_no',
                };

                $depositQuery->where($depositColumn, 'like', '%' . $search . '%');
            }
        }

        if ($this->status !== '') {
            $payoutQuery->where('status', $this->status);
            $depositQuery->where('processing_status', $this->status);
        }

        if ($this->dateFrom !== '') {
            $toDate = $this->dateTo ?: now()->toDateString();

            $payoutQuery->whereDate('initiated_at', '>=', $this->dateFrom)
                ->whereDate('initiated_at', '<=', $toDate);

            $depositQuery->whereDate('transaction_date', '>=', $this->dateFrom)
                ->whereDate('transaction_date', '<=', $toDate);
        } else {
            // Default: current month only
            $payoutQuery->whereMonth('initiated_at', now()->month)
                ->whereYear('initiated_at', now()->year);

            $depositQuery->whereMonth('transaction_date', now()->month)
                ->whereYear('transaction_date', now()->year);
        }

        if ($this->entryType === 'payout') {
            return DB::query()
                ->fromSub($payoutQuery, 'report_rows')
                ->orderByDesc('txn_at');
        }

        if ($this->entryType === 'deposit') {
            return DB::query()
                ->fromSub($depositQuery, 'report_rows')
                ->orderByDesc('txn_at');
        }

        return DB::query()
            ->fromSub($payoutQuery->unionAll($depositQuery), 'report_rows')
            ->orderByDesc('txn_at');
    }

    public function render()
    {
        $reports = $this->baseRowsQuery()->paginate($this->perPage);

        $summary = DB::query()
            ->fromSub($this->baseRowsQuery(), 'summary_rows')
            ->selectRaw('COUNT(*) as total_transactions')
            ->selectRaw('COALESCE(SUM(total_amount), 0) as total_volume')
            ->selectRaw("COALESCE(SUM(CASE WHEN entry_type = 'payout' THEN total_amount ELSE 0 END), 0) as payout_volume")
            ->selectRaw("COALESCE(SUM(CASE WHEN entry_type = 'deposit' THEN total_amount ELSE 0 END), 0) as inward_volume")
            ->selectRaw("COALESCE(SUM(CASE WHEN entry_type = 'payout' THEN 1 ELSE 0 END), 0) as payout_transactions")
            ->selectRaw("COALESCE(SUM(CASE WHEN entry_type = 'deposit' THEN 1 ELSE 0 END), 0) as inward_transactions")
            ->selectRaw("COALESCE(SUM(CASE WHEN normalized_status = 'success' THEN 1 ELSE 0 END), 0) as success_transactions")
            ->first();

        return view('merchant.report-component')
        ->with([
            'reports' => $reports,
            'summary' => $summary,
            'successRate' => ($summary && $summary->total_transactions > 0)
                ? round(($summary->success_transactions / $summary->total_transactions) * 100, 1)
                : 0,
        ])
        ->layout('layouts.app')
        ->layoutData([
            'active' => 'reports',
            'pageTitle' => 'Reports',
            'metaTitle' => 'Reports - M.M.P Fintech Payment Solution',
            'metaDescription' => 'Merchant Reports for M.M.P Fintech Payment Solution',
        ]);
    }
}
