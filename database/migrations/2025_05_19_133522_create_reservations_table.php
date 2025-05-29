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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_template_id')->nullable()->constrained()->onDelete('set null');
            $table->date('event_date');
            $table->time('event_time');
            $table->integer('guest_count');
            $table->string('bride_name')->nullable(); // Untuk event Wedding
            $table->string('groom_name')->nullable(); // Untuk event Wedding
            $table->decimal('total_price', 12, 2);
            $table->string('status')->default('pending_admin_review');
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->decimal('estimated_revenue', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
