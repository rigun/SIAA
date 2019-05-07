<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHargaToDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_spareparts', function (Blueprint $table) {
            $table->double('harga');
        });
        Schema::table('detail_services', function (Blueprint $table) {
            $table->double('harga');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_spareparts', function (Blueprint $table) {
            $table->dropColumn('harga');
        });
        Schema::table('detail_services', function (Blueprint $table) {
            $table->dropColumn('harga');
        });
    }
}
