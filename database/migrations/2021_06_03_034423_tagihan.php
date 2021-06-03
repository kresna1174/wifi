<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tagihan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_pemasangan');
            $table->date('tanggal_tagihan');
            $table->integer('status_bayar');
            $table->integer('deleted');
            $table->timestamp('created_at');
            $table->string('created_by', 255);
            $table->timestamp('updated_at');
            $table->string('updated_by', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tagihan');
    }
}
