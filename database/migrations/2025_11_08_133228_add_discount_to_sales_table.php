<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountToSalesTable extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('discount_amount', 10, 2)->default(0)->after('subtotal');
            $table->decimal('discount_value', 10, 2)->nullable()->after('discount_amount'); // stores percent or fixed numeric
            $table->string('discount_type', 10)->nullable()->after('discount_value'); // 'fixed' or 'percent'
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['discount_amount', 'discount_value', 'discount_type']);
        });
    }
}
