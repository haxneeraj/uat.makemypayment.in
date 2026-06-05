<?php

namespace App\Livewire\Merchant;

use App\Models\Deposit;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;

class InwardReport extends Component
{
    use WithPagination;

    public $searchBy = 'reference';
    public $search = '';
    public $status = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 15;

    protected $queryString = [
        'searchBy' => ['except' => 'reference'],
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'perPage' => ['except' => 15],
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

    public function clearFilters(): void
    {
        $this->searchBy = 'reference';
        $this->search = '';
        $this->status = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->perPage = 15;
        $this->resetPage();
        $this->dispatch('reportFiltersCleared');
    }

    public function downloadCsv()
    {
        $query = $this->activeRowsQuery()->orderBy('id');

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'Transaction ID',
                'Virtual Account',
                'Amount',
                'Transaction Date',
                'Description',
                'Status',
            ]);

            $query->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->alert_sequence_no,
                        $row->virtual_account ?? $row->account_number,
                        number_format((float) $row->amount, 2, '.', ''),
                        $row->transaction_date ? \Carbon\Carbon::parse($row->transaction_date)->format('d M Y, h:i A') : '',
                        $row->transaction_description ?? '-',
                        ucfirst(str_replace('_', ' ', $row->processing_status)),
                    ]);
                }
            });

            fclose($handle);
        }, 'merchant-inward-report-' . now()->format('Ymd-His') . '.csv');
    }

    public function downloadPdf()
    {
        $rows = $this->buildPdfRows(1000);

        $pdf = Pdf::loadView('merchant.reports.inward-pdf', [
            'rows' => $rows,
            'generatedAt' => now(),
            'merchantName' => auth()->user()->name,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'merchant-inward-report-' . now()->format('Ymd-His') . '.pdf');
    }

    private function applyFilters($query)
    {
        $search = trim($this->search);
        if ($search !== '') {
            $col = match ($this->searchBy) {
                'name' => 'remitter_name',
                'bank' => 'remitter_bank',
                default => 'alert_sequence_no',
            };

            if ($this->searchBy === 'account') {
                $query->whereRaw('COALESCE(virtual_account, remitter_account, account_number) like ?', ["%$search%"]);
            } else {
                $query->where($col, 'like', "%$search%");
            }
        }

        if ($this->status !== '') {
            $query->where('processing_status', $this->status);
        }

        if ($this->dateFrom !== '') {
            $toDate = $this->dateTo ?: now()->toDateString();
            $query->whereDate('transaction_date', '>=', $this->dateFrom)
                ->whereDate('transaction_date', '<=', $toDate);
        } else {
            $query->whereDate('transaction_date', now()->toDateString());
        }

        return $query;
    }

    private function inwardRowsQuery()
    {
        return $this->applyFilters(
            Deposit::query()
                ->where('user_id', auth()->id())
                ->select([
                    'id',
                    'alert_sequence_no',
                    'remitter_name',
                    'remitter_bank',
                    'virtual_account',
                    'remitter_account',
                    'account_number',
                    'amount',
                    'processing_status',
                    'transaction_date',
                    'transaction_description',
                    'created_at',
                ])
        )
            ->orderByDesc('transaction_date');
    }

    private function activeRowsQuery()
    {
        return $this->inwardRowsQuery();
    }

    private function buildPdfRows(int $limit = 1000)
    {
        $rows = collect();

        $this->activeRowsQuery()
            ->orderBy('id')
            ->limit($limit)
            ->chunk(250, function ($chunk) use (&$rows) {
                $rows = $rows->concat($chunk);
            });

        return $rows->values();
    }

    public function render()
    {
        $statsQuery = $this->applyFilters(
            Deposit::query()->where('user_id', auth()->id())
        );

        $stats = $statsQuery
            ->selectRaw('
                COUNT(*) as total_count,
                COALESCE(SUM(amount), 0) as total_amount,
                COALESCE(SUM(CASE WHEN processing_status = "success" THEN 1 ELSE 0 END), 0) as success_count,
                COALESCE(SUM(CASE WHEN processing_status IN ("duplicate", "technical_reject") THEN 1 ELSE 0 END), 0) as failed_count
            ')
            ->first();

        $reports = $this->inwardRowsQuery()->paginate($this->perPage);

        $totalTransactions = (int) ($stats->total_count ?? 0);
        $successTransactions = (int) ($stats->success_count ?? 0);
        $failedTransactions = (int) ($stats->failed_count ?? 0);
        $failedVolume = (float) (clone $statsQuery)
            ->whereIn('processing_status', ['duplicate', 'technical_reject'])
            ->sum('amount');

        $successRate = ($totalTransactions > 0)
            ? round(($successTransactions / $totalTransactions) * 100, 1)
            : 0;
        $failedRate = ($totalTransactions > 0)
            ? round(($failedTransactions / $totalTransactions) * 100, 1)
            : 0;

        $summary = [
            'total_transactions' => $totalTransactions,
            'total_volume' => (float) ($stats->total_amount ?? 0),
            'payout_volume' => 0,
            'inward_volume' => (float) ($stats->total_amount ?? 0),
            'payout_transactions' => 0,
            'inward_transactions' => $totalTransactions,
            'success_transactions' => $successTransactions,
            'failed_transactions' => $failedTransactions,
            'failed_volume' => $failedVolume,
            'failed_rate' => $failedRate,
        ];

        return view('merchant.inward-report')
            ->with([
                'reports' => $reports,
                'summary' => (object) $summary,
                'successRate' => $successRate,
                'failedRate' => $failedRate,
            ])
            ->layout('layouts.app')
            ->layoutData([
                'active' => 'reports',
                'pageTitle' => 'Inward Reports',
                'metaTitle' => 'Inward Reports - M.M.P Fintech Payment Solution',
                'metaDescription' => 'Merchant Inward Reports for M.M.P Fintech Payment Solution',
            ]);
    }
}
