<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeerLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peer_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("public_key");
            $table->string("preshared_key");
            $table->string("endpoint");
            $table->string("allowed_ips");
            $table->bigInteger("latest_handshake");
            $table->bigInteger("transfer_rx");
            $table->bigInteger("transfer_tx");
            $table->string("persistent_keepalive");
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
        Schema::dropIfExists('peer_logs');
    }
}
