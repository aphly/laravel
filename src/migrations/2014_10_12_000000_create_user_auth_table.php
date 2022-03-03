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
        Schema::create('user_auth', function (Blueprint $table) {
            $table->id();
            $table->char('uuid',32);
            $table->char('identity_type',16);
            $table->string('identifier',128);
            $table->string('credential',255);
            $table->tinyInteger('verified')->default(0);
            $table->integer('last_login')->unsigned()->nullable();
            $table->string('last_ip',64)->nullable();
            $table->unique(['identity_type','identifier']);
            $table->index('uuid');
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
        Schema::dropIfExists('user_auth');
    }
};
