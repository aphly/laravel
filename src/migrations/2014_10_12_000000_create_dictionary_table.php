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
        Schema::create('dictionary', function (Blueprint $table) {
            $table->id();
            $table->string('name',64)->index();
            $table->integer('sort')->unique()->index();
            $table->integer('pid')->unique()->index();
            $table->tinyInteger('status')->default(1)->comment('1:正常;')->index();
            $table->tinyInteger('is_leaf')->default(1)->index();
            $table->string('icon',255)->nullable();
            $table->string('value',255)->nullable();
            $table->json('json')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dictionary');
    }
};
