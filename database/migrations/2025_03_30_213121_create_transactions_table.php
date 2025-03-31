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
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('phone');
            $table->string('external_id');
            $table->string('checkout_link');
            $table->foreignId('barcodes_id')->constrained('barcodes')->cascadeOnDeletes();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->integer('ppn');
            $table->decimal('total', 10, 2);
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
