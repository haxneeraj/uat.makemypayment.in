<?php

namespace App\Observers;

use App\Mail\InvoiceCreated;
use Illuminate\Support\Facades\Mail;

use App\Models\Invoice;

class InvoiceCreatedObserver
{
    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void
    {
        Mail::to($invoice->user->email)->send(new InvoiceCreated($invoice));
    }
}
