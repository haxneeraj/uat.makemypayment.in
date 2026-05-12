<?php

namespace App\Livewire\Merchant;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice as InvoiceModel;
use Barryvdh\DomPDF\Facade\Pdf;

class Invoice extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function downloadInvoice($invoiceId)
    {
        $invoice = InvoiceModel::with(['items', 'user.merchantKyc'])
            ->where('user_id', auth()->id())
            ->findOrFail($invoiceId);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        
        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, "invoice-{$invoice->invoice_number}.pdf");
    }

    public function render()
    {
        $query = InvoiceModel::where('user_id', auth()->id());

        if ($this->search) {
            $query->where('invoice_number', 'like', '%' . $this->search . '%');
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $invoices = $query->latest()->paginate($this->perPage);

        return view('merchant.invoice', compact('invoices'))
            ->layout('layouts.app')
            ->layoutData([
                'active' => 'invoices',
                'pageTitle' => 'Invoices',
                'metaTitle' => 'Invoices - M.M.P Fintech Payment Solution',
                'metaDescription' => 'Manage your invoices',
            ]);
    }
}
