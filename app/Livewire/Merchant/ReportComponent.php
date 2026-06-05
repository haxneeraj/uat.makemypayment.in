<?php

namespace App\Livewire\Merchant;

use App\Models\Payout;
use Barryvdh\DomPDF\Facade\Pdf;
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

    // Helper method to build base filter query
    private function buildFilteredQuery()
    {
        $merchantId = auth()->id();
        $query = Payout::where('user_id', $merchantId);

        // Apply search filter
        $search = trim($this->search);
        if ($search !== '') {
            $col = match ($this->searchBy) {
                'name' => 'account_holder',
                'bank' => 'bank_name',
                'account' => 'account_number',
                'utr' => 'utr',
                'transaction_id' => 'transaction_id',
                default => 'transaction_id',
            };
            $query->where($col, 'like', "%$search%");
        }

        // Apply status filter
        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        // Apply date filter
        if ($this->dateFrom !== '') {
            $toDate = $this->dateTo ?: now()->toDateString();
            $query->whereBetween('created_at', [
                $this->dateFrom . ' 00:00:00',
                $toDate . ' 23:59:59',
            ]);
        } else {
            $query->whereDate('created_at', now()->toDateString());
        }

        return $query;
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
                '#',
                'Initiated At',
                'Transaction ID',
                'Beneficiary',
                'Bank Details',
                'Mobile',
                'UTR Number',
                'Amount',
                'Fee',
                'Total Amount',
                'Opening Balance',
                'Closing Balance',
                'Mode',
                'Status',
            ]);

            $rowNumber = 0;
            $query->chunk(500, function ($rows) use ($handle, &$rowNumber) {
                foreach ($rows as $row) {
                    $rowNumber++;

                    fputcsv($handle, [
                        $rowNumber,
                        $row->initiated_at ? \Carbon\Carbon::parse($row->initiated_at)->format('d M Y, h:i A') : '',
                        $row->transaction_id,
                        $row->account_holder,
                        trim($row->bank_name . ' | ' . $row->ifsc_code),
                        $row->mobile,
                        $row->utr ?: 'N/A',
                        number_format((float) $row->amount, 2, '.', ''),
                        number_format((float) ($row->fee ?? 0), 2, '.', ''),
                        number_format((float) ($row->total_amount ?? ($row->amount + ($row->fee ?? 0))), 2, '.', ''),
                        number_format((float) ($row->opening_balance ?? 0), 2, '.', ''),
                        number_format((float) ($row->closing_balance ?? 0), 2, '.', ''),
                        $row->mode,
                        ucfirst(str_replace('_', ' ', $row->status)),
                    ]);
                }
            });

            fclose($handle);
        }, 'merchant-report-' . now()->format('Ymd-His') . '.csv');
    }

    public function downloadPdf()
    {
        $rows = $this->buildPdfRows(1000);

        $pdf = Pdf::loadView('merchant.reports.pdf', [
            'rows' => $rows,
            'generatedAt' => now(),
            'merchantName' => auth()->user()->name,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'merchant-report-' . now()->format('Ymd-His') . '.pdf');
    }

    private function payoutRowsQuery()
    {
        return $this->buildFilteredQuery()
            ->with(['refund:id,payout_id,amount,status'])
            ->select([
                'id',
                'transaction_id',
                'merchant_reference_id',
                'account_holder',
                'bank_name',
                'ifsc_code',
                'account_number',
                'mobile',
                'amount',
                'fee',
                'total_amount',
                'opening_balance',
                'closing_balance',
                'status',
                'mode',
                'utr',
                'initiated_at',
                'processed_at',
                'created_at',
            ])
            ->orderByDesc('initiated_at');
    }

    private function activeRowsQuery()
    {
        return $this->payoutRowsQuery();
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
        // Get filtered query for statistics
        $baseQuery = $this->buildFilteredQuery();

        // Get all statistics in a single query
        $stats = $baseQuery
            ->selectRaw('
                COUNT(*) as total_count,
                COALESCE(SUM(total_amount), 0) as total_amount,
                COALESCE(SUM(CASE WHEN status = "success" THEN 1 ELSE 0 END), 0) as success_count
            ')
            ->first();

        $reports = $this->payoutRowsQuery()->paginate($this->perPage);

        $totalTransactions = $stats->total_count ?? 0;
        $successTransactions = $stats->success_count ?? 0;
        $failedTransactions = (int) $baseQuery
            ->clone()
            ->where('status', 'failed')
            ->count();
        $failedVolume = (float) $baseQuery
            ->clone()
            ->where('status', 'failed')
            ->sum('total_amount');
        $successRate = ($totalTransactions > 0)
            ? round(($successTransactions / $totalTransactions) * 100, 1)
            : 0;
        $failedRate = ($totalTransactions > 0)
            ? round(($failedTransactions / $totalTransactions) * 100, 1)
            : 0;

        $summary = [
            'total_transactions' => $totalTransactions,
            'total_volume' => (float) ($stats->total_amount ?? 0),
            'payout_volume' => (float) ($stats->total_amount ?? 0),
            'inward_volume' => 0,
            'payout_transactions' => $totalTransactions,
            'inward_transactions' => 0,
            'success_transactions' => $successTransactions,
            'failed_transactions' => $failedTransactions,
            'failed_volume' => $failedVolume,
            'failed_rate' => $failedRate,
        ];

        return view('merchant.report-component')
            ->with([
                'reports' => $reports,
                'summary' => (object) $summary,
                'successRate' => $successRate,
                'failedRate' => $failedRate,
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
