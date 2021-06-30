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
        if (Schema::hasColumn('pemasangan', 'replicate_tanggal_generate'))
        {
            Schema::table('pemasangan', function (Blueprint $table)
            {
                $table->dropColumn('replicate_tanggal_generate');
            });
        }
        if (Schema::hasColumn('pemasangan', 'replicate_tanggal_generate_terakhir'))
        {
            Schema::table('pemasangan', function (Blueprint $table)
            {
                $table->dropColumn('replicate_tanggal_generate_terakhir');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
