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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->boolean('notify_reservation_updates')->default(true);
            $table->boolean('notify_payment_updates')->default(true);
            $table->boolean('notify_promotions')->default(false);
            $table->boolean('notify_newsletters')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'address',
                'city',
                'state',
                'postal_code',
                'country',
                'notify_reservation_updates',
                'notify_payment_updates',
                'notify_promotions',
                'notify_newsletters'
            ]);
        });
    }
};
