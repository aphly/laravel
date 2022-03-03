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
        Schema::create('user', function (Blueprint $table) {
            $table->char('uuid',32)->primary();
            $table->string('nickname',32)->nullable();
            $table->string('token',128)->unique();
            $table->integer('token_expire')->unsigned();
            $table->string('avatar',255)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1:正常; 2:冻结');
            $table->tinyInteger('gender')->default(1);
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
        Schema::dropIfExists('user');
    }
};
