<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->decimal('amount', '10', '2');

            $table->uuid('payer');
            $table->foreign('payer')->references('id')->on('users');

            $table->dateTime('due_on');

            $table->decimal('vat', '10', '2')->nullable();
            $table->boolean('is_vat_inclusive')->default(false);
            $table->decimal('total_amount', '10', '2');
            $table->decimal('remaining_amount', '10', '2')->default(0);

            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('transaction_statuses');

            $table->uuid('created_by');
            $table->foreign('created_by')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
