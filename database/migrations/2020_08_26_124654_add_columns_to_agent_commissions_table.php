<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToAgentCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_commissions', function (Blueprint $table) {
            $table->string('sum');
            $table->string('price_brutto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_commissions', function (Blueprint $table) {
            $table->dropColumn('price_brutto');
            $table->dropColumn('sum');

        });
    }
}
