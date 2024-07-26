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
        Schema::table('angsuran', function (Blueprint $table) {
            $table->boolean('lunas')->default(false)->after('total');
            $table->text('snap_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('angsuran', function (Blueprint $table) {
            //
            $table->dropColumn('lunas');
            $table->dropColumn('snap_token');
        });
    }
};
