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
        Schema::create('blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->date('blocked_until')->nullable();
            $table->text('reason')->nullable();
            $table->boolean('is_recurring_yearly')->default(false); // Untuk tanggal yang diblokir setiap tahun (misal: hari libur)
            $table->string('blocked_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_dates');
    }
};
