<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            // Tambah harga_beli sebelum harga_jual
            $table->bigInteger('harga_beli')->nullable()->after('id_satuan');
        });

        // Rename harga → harga_jual (pisah agar tidak bentrok di driver tertentu)
        Schema::table('barangs', function (Blueprint $table) {
            $table->renameColumn('harga', 'harga_jual');
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->renameColumn('harga_jual', 'harga');
        });

        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn('harga_beli');
        });
    }
};
