<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_stoks', function (Blueprint $table) {
            $table->bigInteger('harga_beli')->nullable()->after('jumlah');
            $table->bigInteger('harga_jual')->nullable()->after('harga_beli');
            $table->integer('stok_awal')->nullable()->after('harga_jual');
            $table->integer('stok_akhir')->nullable()->after('stok_awal');
            $table->bigInteger('total_cash')->nullable()->after('stok_akhir');
            $table->bigInteger('profit')->nullable()->after('total_cash');
            $table->boolean('is_bonus')->default(false)->after('profit');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_stoks', function (Blueprint $table) {
            $table->dropColumn([
                'harga_beli',
                'harga_jual',
                'stok_awal',
                'stok_akhir',
                'total_cash',
                'profit',
                'is_bonus',
            ]);
        });
    }
};
