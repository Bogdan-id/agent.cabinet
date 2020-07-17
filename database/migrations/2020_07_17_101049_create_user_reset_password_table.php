<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserResetPasswordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_reset_passwords', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('verification_code');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->index(['user_id', 'verification_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_reset_passwords');
    }
}
