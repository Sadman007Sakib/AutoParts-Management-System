<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {

            $table->string('status')->default('pending')->after('source');

            $table->string('payment_status')->default('unpaid')->after('status');

            $table->string('delivery_status')->default('pending')->after('payment_status');

            $table->string('phone')->nullable()->after('delivery_status');

            $table->text('address')->nullable()->after('phone');

        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {

            $table->dropColumn([
                'source',
                'status',
                'payment_status',
                'delivery_status',
                'phone',
                'address'
            ]);

        });
    }
};