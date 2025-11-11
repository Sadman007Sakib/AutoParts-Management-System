<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('sales', function (Blueprint $table) {
        $table->decimal('tax_rate', 5, 2)->default(0);   // percent
        $table->decimal('tax_amount', 10, 2)->default(0); // absolute
        // if you want to keep subtotal separate:
        $table->decimal('subtotal', 12, 2)->nullable()->after('slip_no');
    });
}
public function down()
{
    Schema::table('sales', function (Blueprint $table) {
        $table->dropColumn(['tax_rate','tax_amount','subtotal']);
    });
}

};
