<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceBruttoColumnToLeasingRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leasing_requests', function (Blueprint $table) {
            $table->string('price_brutto')->after('leasing_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leasing_requests', function (Blueprint $table) {
            $table->dropColumn('price_brutto');
        });
    }
}
