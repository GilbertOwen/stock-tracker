<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuan';
    protected $primaryKey = 'id_satuan';
    protected $fillable = ['nama_satuan'];
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_satuan', 'id_satuan');
    }
    
}
