<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Util\CIDR;

class CreateAvailableIpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('available_ips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("ip");
            $table->string("endpoint")->nullable();
            $table->string("public_key")->nullable();
            $table->boolean("is_assigned")->nullable();
            $table->longText("config")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('available_ips');
    }

}
