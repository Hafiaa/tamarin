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
        Schema::table('settings', function (Blueprint $table) {
            // Ubah tipe kolom payload dari json ke text
            $table->text('payload')->nullable()->change();
            
            // Hapus kolom locked jika tidak digunakan
            if (Schema::hasColumn('settings', 'locked')) {
                $table->dropColumn('locked');
            }
            
            // Tambahkan indeks jika belum ada
            $table->index('group');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Kembalikan ke tipe data semula
            $table->json('payload')->change();
            
            // Tambahkan kembali kolom locked
            if (!Schema::hasColumn('settings', 'locked')) {
                $table->boolean('locked')->default(false);
            }
            
            // Hapus indeks yang tidak diperlukan
            $table->dropIndex(['group']);
            $table->dropIndex(['name']);
        });
    }
};
