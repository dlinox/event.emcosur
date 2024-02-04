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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending');
            $table->enum('payment_type', ['online', 'cash'])->default('online');
            $table->string('payment_method')->nullable();
            $table->date('payment_date')->nullable();
            $table->char('payment_transaction_id', 20)->nullable();
            $table->double('payment_amount', 8, 2)->nullable();
            $table->string('payment_image')->nullable();
            $table->string('payment_bank')->nullable();
            $table->char('payment_currency', 3)->default('PEN');

            $table->boolean('is_active')->default(true);

            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('restrict')->onUpdate('no action');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('restrict')->onUpdate('no action');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('no action');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
