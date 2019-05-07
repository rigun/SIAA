<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_detailtransaksi');
            $table->foreign('id_detailtransaksi')->references('id')->on('detail_transaksis');
            $table->unsignedBigInteger('id_service');
            $table->foreign('id_service')->references('id')->on('jasa_services');
            $table->integer('jumlah');
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
        Schema::dropIfExists('detail_services');
    }
}
