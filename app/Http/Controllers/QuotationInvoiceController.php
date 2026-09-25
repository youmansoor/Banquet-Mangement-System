<?php

namespace App\Http\Controllers;

use App\Models\Invoice;

class QuotationInvoiceController extends Controller
{
    public function show(Invoice $invoice)
    {
        $this->authorizeQuotationInvoice($invoice);
        $invoice->load(['customer', 'quotation.lawnType', 'tenant', 'items']);

        return view('tenant.invoices.quotation-show', compact('invoice'));
    }

    public function print(Invoice $invoice)
    {
        $this->authorizeQuotationInvoice($invoice);
        $invoice->load(['customer', 'quotation.lawnType', 'tenant', 'items']);

        return view('tenant.invoices.quotation-print', compact('invoice'));
    }

    private function authorizeQuotationInvoice(Invoice $invoice): void
    {
        abort_if($invoice->tenant_id !== auth()->user()->tenant_id, 403);
        abort_if(! $invoice->quotation_id, 404);
    }
}
