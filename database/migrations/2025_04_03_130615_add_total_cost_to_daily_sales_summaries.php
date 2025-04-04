<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('daily_sales_summaries', function (Blueprint $table) {
            $table->decimal('total_cost', 10, 2)->default(0)->after('total_profit');
        });
    }

    public function down()
    {
        Schema::table('daily_sales_summaries', function (Blueprint $table) {
            $table->dropColumn('total_cost');
        });
    }
};
