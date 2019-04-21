<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCabangSparepartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cabang_spareparts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_sparepart');
            $table->unsignedBigInteger('id_cabang');
            $table->foreign('code_sparepart')->references('code')->on('spareparts');
            $table->foreign('id_cabang')->references('id')->on('cabangs');
            $table->integer('stock');
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
        Schema::dropIfExists('cabang_spareparts');
    }
}
