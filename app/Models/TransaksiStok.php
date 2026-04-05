<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiStok extends Model
{
    protected $table = 'transaksi_stoks';
    protected $primaryKey = 'id_transaksi';
    protected $fillable = ['id_stok', 'jenis', 'jumlah', 'deskripsi'];
    public function stok()
    {
        return $this->belongsTo(Stok::class, 'id_stok', 'id_stok');
    }
}
