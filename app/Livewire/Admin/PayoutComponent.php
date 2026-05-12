<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Payout;
use Livewire\WithPagination;
use Carbon\Carbon;

class PayoutComponent extends Component
{
    use WithPagination;

    public $searchColumn = 'utr';
    public $search = '';
    public $date = '';
    public $status = '';
    public $perPage = 10;

    protected $queryString = [
        'searchColumn' => ['except' => 'utr'],
        'search' => ['except' => ''],
        'date' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function getPayoutStats()
    {
        $today = Carbon::today();
        return [
            'today_count' => Payout::whereDate('created_at', $today)->count(),
            'today_volume' => Payout::whereDate('created_at', $today)->sum('amount'),
            'success_count' => Payout::where('status', 'success')->count(),
            'success_volume' => Payout::where('status', 'success')->sum('amount'),
            'pending_count' => Payout::where('status', 'pending')->count(),
            'pending_volume' => Payout::where('status', 'pending')->sum('amount'),
            'failed_count' => Payout::where('status', 'failed')->count(),
            'failed_volume' => Payout::where('status', 'failed')->sum('amount'),
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $payouts = Payout::with(['user'])
            ->when($this->search, function($query) {
                $query->where($this->searchColumn, 'like', '%'.$this->search.'%');
            })
            ->when($this->date, function($query) {
                $query->whereDate('created_at', $this->date);
            })
            ->when($this->status, function($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate($this->perPage);

        return view('admin.payout-component', [
            'payouts' => $payouts,
            'stats' => $this->getPayoutStats()
        ])
        ->layout('layouts.admin')
        ->layoutData([
            'active' => 'payouts',
            'pageTitle' => 'Payouts Management',
            'metaTitle' => 'Payouts Management - MMP Fintech',
            'metaDescription' => 'Manage and monitor all payout transactions.',
        ]);
    }
}
