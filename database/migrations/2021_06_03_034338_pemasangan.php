<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Pemasangan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemasangan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_pelanggan');
            $table->string('alamat_pemasangan', 255);
            $table->double('tarif', 16);
            $table->date('tanggal_pemasangan', 255);
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
        Schema::dropIfExists('pemasangan');
    }
}
