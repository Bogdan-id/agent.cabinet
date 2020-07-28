<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBitrixIdFieldToLeasingRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leasing_requests', function (Blueprint $table) {
            $table->string('bitrix_id')->after('id');
            
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
            $table->dropColumn('bitrix_id');
        });
    }
}
