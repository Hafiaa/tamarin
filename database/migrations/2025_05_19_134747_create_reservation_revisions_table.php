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
        Schema::create('reservation_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->text('revision_details');
            $table->json('changes')->nullable(); // Menyimpan perubahan dalam format JSON
            $table->string('status')->default('pending_revision_review'); // pending_revision_review, approved, rejected
            $table->text('admin_notes')->nullable();
            $table->decimal('price_adjustment', 12, 2)->default(0); // Penyesuaian harga (bisa positif atau negatif)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_revisions');
    }
};
