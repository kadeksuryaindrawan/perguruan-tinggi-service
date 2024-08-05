<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_bantuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_desa')->nullable();
            $table->foreignId('id_permasalahan')->nullable();
            $table->foreignId('user_id');
            $table->string('desa');
            $table->text('potensi');
            $table->text('permasalahan');
            $table->text('bantuan');
            $table->string('perguruan_tinggi');
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
        Schema::dropIfExists('history_bantuans');
    }
};
