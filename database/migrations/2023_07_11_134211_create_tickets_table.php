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
        Schema::create('tickets', function (Blueprint $table) {

            $table->foreignUlid('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignUlid('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->ulid('id')->primary();
            $table->string('ticket_type');
            $table->double('amount', 20, 8);
            $table->integer('quantity');
            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
