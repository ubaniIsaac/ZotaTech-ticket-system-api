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
            Schema::create('events', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->string('title');
                $table->text('description');
                $table->string('location');
                $table->string('type');
                $table->string('image');
                $table->string('status')->default('active');
                $table->integer('price');
                $table->integer('capacity');
                $table->integer('available_seats');
                $table->integer('sold_seats')->default(0);
                $table->date('start_date');
                $table->date('end_date');
                $table->time('time');
                $table->timestamps();
                $table->foreignUlid('user_id')->constrained('users')->onDelete('cascade');
    
            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
