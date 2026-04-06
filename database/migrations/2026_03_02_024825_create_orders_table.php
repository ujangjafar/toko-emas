<?php
// database/migrations/2024_01_01_000006_create_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique(); // Nomor invoice unik
            $table->decimal('total_price', 15, 2);
            $table->enum('status', ['pending', 'diproses', 'selesai', 'cancelled'])->default('pending');
            $table->string('payment_method')->default('transfer'); // qris, transfer, card
            $table->string('payment_status')->default('pending'); // pending, paid, failed
            $table->string('payment_proof')->nullable(); // Bukti pembayaran (upload)
            $table->timestamp('paid_at')->nullable();
            $table->text('address');
            $table->string('phone');
            $table->string('email');
            $table->text('notes')->nullable();
            $table->timestamp('order_date');
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};