<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Pelanggan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_pelanggan', 255);
            $table->integer('no_telepon');
            $table->integer('no_identitas');
            $table->string('alamat', 255);
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
        Schema::dropIfExists('pelanggan');
    }
}
