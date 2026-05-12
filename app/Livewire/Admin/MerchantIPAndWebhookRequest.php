<?php

namespace App\Livewire\Admin;

use App\Events\IpWebhookApproved;
use App\Events\IpWebhookRejected;
use App\Models\APIActivationRequest;
use Livewire\Component;
use Livewire\WithPagination;

class MerchantIPAndWebhookRequest extends Component
{
    use WithPagination;

    public $search = '';

    public function approveRequest($id)
    {
        $request = APIActivationRequest::findOrFail($id);
        $request->update(['status' => 'verified']);

        event(new IpWebhookApproved($request->fresh('user')));

        session()->flash('success', 'Request has been approved successfully.');
    }

    public function rejectRequest($id, $remark = '')
    {
        $request = APIActivationRequest::findOrFail($id);
        $request->update([
            'status' => 'rejected',
            'remark' => $remark,
        ]);

        event(new IpWebhookRejected($request->fresh('user')));

        session()->flash('success', 'Request has been rejected successfully.');
    }

    public function render()
    {
        $requests = APIActivationRequest::where('status', 'pending')
        ->when(!blank($this->search), function($query){
            $query->where(function($q){
                $q->where('ip', 'like', '%' . $this->search . '%')
                ->orWhere('webhook_url', 'like', '%' . $this->search . '%')
                ->orWhere('webhook_secret', 'like', '%' . $this->search . '%')
                ->orWhereHas('user', function($q){
                    $q->where('full_name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('merchant_id', 'like', '%' . $this->search . '%');
                });
            });
        })->paginate();
        return view('admin.merchant-i-p-and-webhook-request', [
            'requests' => $requests,
        ])
        ->layout('layouts.admin')
        ->layoutData([
            'active' => 'ip-and-callback-requests',
            'pageTitle' => 'IP & Webhook Requests',
            'metaTitle' => 'IP & Webhook Requests - MMP Fintech',
            'metaDescription' => 'View and manage all IP & Webhook requests in the MMP Fintech admin dashboard.',
        ]);
    }
}
