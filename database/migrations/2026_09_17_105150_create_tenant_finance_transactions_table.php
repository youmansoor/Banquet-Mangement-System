<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'tenant_finance_transactions',
            function (Blueprint $table) {

                $table->id();


                /*
                |--------------------------------------------------------------------------
                | TENANT
                |--------------------------------------------------------------------------
                */

                $table->foreignId('tenant_id')
                    ->constrained('tenants')
                    ->cascadeOnDelete();


                /*
                |--------------------------------------------------------------------------
                | HARD-CODED TRANSACTION TYPE
                |--------------------------------------------------------------------------
                |
                | customer_payment
                | vendor_payment
                | banquet_expense
                |
                */

                $table->string(
                    'transaction_type',
                    50
                );


                /*
                |--------------------------------------------------------------------------
                | PAYMENT CATEGORY
                |--------------------------------------------------------------------------
                |
                | This points to tenant_finance_masters.
                |
                | master_type = payment_category
                |
                */

                $table->foreignId('payment_category_id')
                    ->constrained('tenant_finance_masters')
                    ->restrictOnDelete();


                /*
                |--------------------------------------------------------------------------
                | BENEFICIARY
                |--------------------------------------------------------------------------
                */

                $table->foreignId('beneficiary_id')
                    ->nullable()
                    ->constrained('tenant_finance_masters')
                    ->nullOnDelete();


                /*
                |--------------------------------------------------------------------------
                | VENDOR
                |--------------------------------------------------------------------------
                |
                | Vendor is a real vendor record.
                |
                */

                $table->foreignId('vendor_id')
                    ->nullable()
                    ->constrained('vendors')
                    ->nullOnDelete();


                /*
                |--------------------------------------------------------------------------
                | CUSTOMER
                |--------------------------------------------------------------------------
                */

                $table->foreignId('customer_id')
                    ->nullable()
                    ->constrained('customers')
                    ->nullOnDelete();


                /*
                |--------------------------------------------------------------------------
                | BOOKING
                |--------------------------------------------------------------------------
                */

                $table->foreignId('booking_id')
                    ->nullable()
                    ->constrained('bookings')
                    ->nullOnDelete();


                /*
                |--------------------------------------------------------------------------
                | INVOICE
                |--------------------------------------------------------------------------
                */

                $table->foreignId('invoice_id')
                    ->nullable()
                    ->constrained('invoices')
                    ->nullOnDelete();


                /*
                |--------------------------------------------------------------------------
                | SNAPSHOT REFERENCES
                |--------------------------------------------------------------------------
                */

                $table->string('job_number')
                    ->nullable();

                $table->string('bill_number')
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | DESCRIPTION
                |--------------------------------------------------------------------------
                */

                $table->string('item_description')
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | VENDOR DOCUMENT
                |--------------------------------------------------------------------------
                */

                $table->string('vendor_invoice')
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | TENANT BANK
                |--------------------------------------------------------------------------
                */

                $table->foreignId('tenant_bank_id')
                    ->nullable()
                    ->constrained('tenant_banks')
                    ->nullOnDelete();


                /*
                |--------------------------------------------------------------------------
                | CHEQUE
                |--------------------------------------------------------------------------
                */

                $table->string('cheque_number')
                    ->nullable();

                $table->date('cheque_date')
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | DEBIT / CREDIT
                |--------------------------------------------------------------------------
                */

                $table->enum(
                    'transaction_direction',
                    [
                        'dr',
                        'cr',
                    ]
                );


                /*
                |--------------------------------------------------------------------------
                | AMOUNT
                |--------------------------------------------------------------------------
                */

                $table->decimal(
                    'amount',
                    15,
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | TRANSACTION DATE
                |--------------------------------------------------------------------------
                */

                $table->date('transaction_date');


                /*
                |--------------------------------------------------------------------------
                | REMARKS
                |--------------------------------------------------------------------------
                */

                $table->text('remarks')
                    ->nullable();


                $table->timestamps();


                /*
                |--------------------------------------------------------------------------
                | INDEXES
                |--------------------------------------------------------------------------
                */

                $table->index(
                    [
                        'tenant_id',
                        'transaction_date',
                    ],
                    'tft_tenant_date_idx'
                );


                $table->index(
                    [
                        'tenant_id',
                        'transaction_type',
                    ],
                    'tft_tenant_type_idx'
                );


                $table->index(
                    [
                        'tenant_id',
                        'payment_category_id',
                    ],
                    'tft_tenant_category_idx'
                );


                $table->index(
                    [
                        'tenant_id',
                        'customer_id',
                    ],
                    'tft_tenant_customer_idx'
                );


                $table->index(
                    [
                        'tenant_id',
                        'booking_id',
                    ],
                    'tft_tenant_booking_idx'
                );


                $table->index(
                    [
                        'tenant_id',
                        'invoice_id',
                    ],
                    'tft_tenant_invoice_idx'
                );


                $table->index(
                    [
                        'tenant_id',
                        'vendor_id',
                    ],
                    'tft_tenant_vendor_idx'
                );

            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'tenant_finance_transactions'
        );
    }
};