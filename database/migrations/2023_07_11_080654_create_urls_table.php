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
            Schema::create('urls', function (Blueprint $table) {
                $table->ulid('id')->primary()->uniqid();
                $table->string('long_url');
                $table->string('short_id');
                $table->string('short_url');
                $table->integer('clicks')->default(0);
                $table->foreignUlid('event_id')->constrained('events')->onDelete('cascade');            
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urls');
    }
};
