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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('penjualan_id')->unsigned();
            $table->bigInteger('angsuran_id')->unsigned()->nullable();
            $table->date('tanggal');
            $table->string('bank');
            $table->string('atas_nama');
            $table->string('bukti');
            $table->smallInteger('status')->default(0);
            $table->text('keterangan_status')->nullable();
            $table->text('keterangan_pembayaran')->nullable();
            $table->timestamps();
            $table->foreign('penjualan_id')->references('id')->on('penjualan');
            $table->foreign('angsuran_id')->references('id')->on('angsuran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
