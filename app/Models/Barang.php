<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barangs';
    protected $primaryKey = 'id_barang';
    protected $fillable = ['nama', 'harga', 'id_satuan'];
    
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
        return $this->hasMany(TransaksiStok::class, 'id_barang', 'id_barang');
    }
}
