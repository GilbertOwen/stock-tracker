<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiStok extends Model
{
    protected $table = 'transaksi_stoks';
    protected $primaryKey = 'id_transaksi';
    protected $fillable = [
        'stok_id',
        'jenis',
        'jumlah',
        'harga_beli',
        'harga_jual',
        'stok_awal',
        'stok_akhir',
        'total_cash',
        'profit',
        'is_bonus',
        'deskripsi',
    ];

    protected $casts = [
        'is_bonus' => 'boolean',
    ];

    public function stok()
    {
        return $this->belongsTo(Stok::class, 'stok_id', 'id_stok');
    }

    public function barang()
    {
        return $this->hasOneThrough(
            Barang::class,
            Stok::class,
            'id_stok',     // FK di stoks
            'id_barang',   // PK di barangs
            'stok_id',     // FK di transaksi_stoks
            'id_barang'    // FK di stoks
        );
    }
}
