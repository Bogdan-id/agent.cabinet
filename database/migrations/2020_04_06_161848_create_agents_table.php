<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unique();
            $table->string('bitrix_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('patronymic')->nullable();
            $table->string('company_type');
            $table->string('company_name');
            $table->string('position');
            $table->string('status')->nullable();
            $table->integer('passport_type_id')->nullable();
            $table->string('inn')->nullable();
            $table->string('birth')->nullable();
            $table->string('card_number')->nullable();
            $table->string('ab_size')->nullable();
            $table->integer('manager_id')->nullable();
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
        Schema::dropIfExists('agents');
    }
}
