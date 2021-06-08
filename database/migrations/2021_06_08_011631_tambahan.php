<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tambahan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pelanggan', function($table) {
            $table->double('no_pelanggan');
            $table->integer('identitas');
        });
        Schema::table('pemasangan', function($table) {
            $table->double('no_pemasangan');
        });
        Schema::table('tagihan', function($table) {
            $table->double('tagihan');
            $table->double('sisa_tagihan');
            $table->date('tanggal_tagihan_terakhir');
        });
        Schema::create('pembayaran_detail', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_pembayaran');
            $table->integer('id_tagihan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pelanggan', function($table) {
            $table->dropIfExists('no_pelanggan');
            $table->dropIfExists('identitas');
        });
        Schema::table('pemasangan', function($table) {
            $table->dropIfExists('no_pemasangan');
        });
        Schema::table('tagihan', function($table) {
            $table->dropIfExists('tagihan');
            $table->dropIfExists('sisa_tagihan');
            $table->dropIfExists('tanggal_tagihan_terakhir');
        });
        Schema::drop('pembayaran_detail');
    }
}