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
        Schema::create('package_template_service_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_template_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_item_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('custom_price', 12, 2)->nullable(); // Jika harga berbeda dari harga default service_item
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_template_service_item');
    }
};
