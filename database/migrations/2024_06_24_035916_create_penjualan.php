<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->date('tanggal');
            $table->string('no_penjualan')->unique();
            $table->integer('sub_total')->default(0);
            $table->integer('ongkir')->default(0);
            $table->integer('total')->default(0);
            $table->smallInteger('status')->default(0);
            $table->boolean('lunas')->default(false);
            $table->boolean('is_kirim')->default(false);
            $table->string('kota');
            $table->text('alamat');
            $table->boolean('kredit')->default(false)->comment('0: kontan, 1: kredit');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
