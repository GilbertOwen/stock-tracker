<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barangs';
    protected $primaryKey = 'id_barang';
    protected $fillable = ['nama', 'harga_beli', 'harga_jual', 'id_satuan'];

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan', 'id_satuan');
    }
    public function stok()
    {
        return $this->hasOne(Stok::class, 'id_barang', 'id_barang');
    }
    public function transaksiStok()
    {
        return $this->hasManyThrough(
            TransaksiStok::class,
            Stok::class,
            'id_barang',  // FK di stoks
            'stok_id',    // FK di transaksi_stoks
            'id_barang',  // PK di barangs
            'id_stok'     // PK di stoks
        );
    }
}
