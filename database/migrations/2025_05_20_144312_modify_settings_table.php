<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah tipe kolom payload dari json ke text
        if (Schema::hasColumn('settings', 'payload')) {
            DB::statement('ALTER TABLE settings MODIFY payload TEXT NULL');
        }
        
        // Hapus kolom locked jika ada
        if (Schema::hasColumn('settings', 'locked')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('locked');
            });
        }
        
        // Tambahkan indeks jika belum ada
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'group_index')) {
                $table->index('group', 'group_index');
            }
            if (!Schema::hasColumn('settings', 'name_index')) {
                $table->index('name', 'name_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan tipe kolom payload ke json
        if (Schema::hasColumn('settings', 'payload')) {
            DB::statement('ALTER TABLE settings MODIFY payload JSON NOT NULL');
        }
        
        // Tambahkan kembali kolom locked
        if (!Schema::hasColumn('settings', 'locked')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->boolean('locked')->default(false);
            });
        }
        
        // Hapus indeks
        Schema::table('settings', function (Blueprint $table) {
            $table->dropIndex('group_index');
            $table->dropIndex('name_index');
        });
    }
};
