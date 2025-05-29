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
        Schema::create('custom_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, custom_awaiting_quote_acceptance, custom_package_agreed
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->decimal('total_price', 12, 2)->nullable();
            $table->json('selected_items')->nullable(); // Menyimpan ID service_items dan menu_items yang dipilih
            $table->json('custom_items')->nullable(); // Menyimpan item ad-hoc yang ditambahkan admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_packages');
    }
};
