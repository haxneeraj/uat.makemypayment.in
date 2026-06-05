<?php

namespace App\Livewire\Merchant;

use Livewire\Component;
use App\Models\Payout;
use Livewire\WithPagination;
use Carbon\Carbon;
use App\Services\Van\VanService;


class PayoutComponent extends Component
{   
    use WithPagination;

    public $showVerificationBlockModal = false;

    public $searchColumn = 'account_holder';
    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $status = '';
    public $perPage = 10;

    protected $queryString = [
        'searchColumn' => ['except' => 'account_holder'],
        'search' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function paginationView()
    {
        return 'components.custom-pagination';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->searchColumn = 'account_holder';
        $this->search       = '';
        $this->dateFrom     = '';
        $this->dateTo       = '';
        $this->status       = '';
        $this->resetPage();
        $this->dispatch('payoutFiltersCleared');
    }

    public function openOneTimePayoutModal(): void
    {
        if (!$this->canPerformPayoutActions()) {
            $this->showVerificationBlockModal = true;
            return;
        }

        $this->dispatch('openPayoutModal');
    }

    public function openBulkPayout()
    {
        if (!$this->canPerformPayoutActions()) {
            $this->showVerificationBlockModal = true;
            return;
        }

        return redirect()->route('merchant.bulk-payout');
    }

    public function closeVerificationBlockModal(): void
    {
        $this->showVerificationBlockModal = false;
    }

    private function canPerformPayoutActions(): bool
    {
        $merchant = auth()->user();

        return $merchant->status === 'active'
            && $merchant->kyc_status === 'verified'
            && $merchant->van_status === 'verified';
    }

    public function render()
    {
        $merchant = auth()->user();

        $dateFrom = Carbon::parse($this->dateFrom)->startOfDay();
        $dateTo   = Carbon::parse($this->dateTo)->endOfDay();

        $todayStart = now()->startOfDay();
        $todayEnd   = now()->endOfDay();

        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $baseQuery = Payout::query()
            ->where('user_id', $merchant->id);

        /*
        |--------------------------------------------------------------------------
        | Payout Listing
        |--------------------------------------------------------------------------
        */

        $payouts = (clone $baseQuery)
        ->with([
            'refund:id,payout_id,amount,status',
        ])
        ->whereBetween('created_at', [$dateFrom, $dateTo])
        ->filter([
            'searchColumn' => $this->searchColumn,
            'search'       => $this->search,
            'status'       => $this->status,
        ])
        ->latest('id')
        ->paginate($this->perPage);

        /*
        |--------------------------------------------------------------------------
        | Today's Stats (Single Optimized Query)
        |--------------------------------------------------------------------------
        */

        $todayStats = (clone $baseQuery)
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->selectRaw("
                COUNT(*) as total_transactions,

                SUM(total_amount) as total_amount,

                SUM(
                    CASE 
                        WHEN status != 'failed' 
                        THEN total_amount 
                        ELSE 0 
                    END
                ) as transferred_amount,

                SUM(
                    CASE 
                        WHEN status = 'failed' 
                        THEN total_amount 
                        ELSE 0 
                    END
                ) as failed_amount,

                SUM(
                    CASE 
                        WHEN status != 'failed' 
                        THEN 1 
                        ELSE 0 
                    END
                ) as success_transactions
            ")
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Success Rate
        |--------------------------------------------------------------------------
        */

        $successRate = $todayStats->total_transactions > 0
            ? round(
                ($todayStats->success_transactions / $todayStats->total_transactions) * 100,
                2
            )
            : 0;

        return view('merchant.payout', [
            'payouts'                    => $payouts,

            // Amount Stats
            'todayTotalAmount'           => $todayStats->total_amount ?? 0,
            'todayTransferredAmount'     => $todayStats->transferred_amount ?? 0,
            'todayFailedAmount'          => $todayStats->failed_amount ?? 0,

            // Transaction Stats
            'todayTotalTransactionCount' => $todayStats->total_transactions ?? 0,

            // Rate
            'successRate'                => $successRate,

            // Merchant Limits
            'dailyTransferLimit'         => $merchant->daily_transfer_limit,
        ])
        ->layout('layouts.app')
        ->layoutData([
            'active' => 'payouts',
            'pageTitle' => 'Payouts',
            'metaTitle' => 'Payouts - M.M.P Fintech Payment Solution',
            'metaDescription' => 'Merchant Payouts for M.M.P Fintech Payment Solution',
        ]);
    }
}
