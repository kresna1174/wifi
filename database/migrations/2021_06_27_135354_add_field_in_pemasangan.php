<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldInPemasangan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pemasangan', function (Blueprint $table) {
            $table->date('replicate_tanggal_generate')->after('tanggal_generate');
            $table->date('replicate_tanggal_generate_terakhir')->after('tanggal_generate_terakhir');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pemasangan', function (Blueprint $table) {
            $table->dropColumn('replicate_tanggal_generate');
            $table->dropColumn('replicate_tanggal_generate_terakhir');
        });
    }
}
