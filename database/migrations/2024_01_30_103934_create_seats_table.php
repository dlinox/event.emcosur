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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->char('row', 1);
            $table->integer('number');
            $table->decimal('price', 8, 2);
            $table->enum('status', ['available', 'reserved', 'sold'])->default('available');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('grandstand_id');
            $table->foreign('grandstand_id')->references('id')->on('grandstands')->onDelete('restrict')->onUpdate('no action');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
