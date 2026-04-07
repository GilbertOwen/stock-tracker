<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $primaryKey = 'id_satuan';
    protected $fillable = ['nama'];
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_satuan', 'id_satuan');
    }
    
}
