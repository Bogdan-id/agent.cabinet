<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeasingRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leasing_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('agent_id');
            $table->integer('client_type_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('patronymic');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('region');
            $table->string('leasing_object');
            $table->integer('advance');
            $table->string('leasing_term');
            $table->string('leasing_amount');
            $table->string('status_id')->default(1);
            $table->string('graph_type');
            $table->json('legal_info');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leasing_requests');
    }
}
