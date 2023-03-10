<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTransaksi extends Model
{
    use HasFactory;

    protected $table = "jenis_transaksi";
    protected $primaryKey = "id";
    protected $fillable = ['jenis_transaksi'];
    public $timestamps = true;

    public function transaksi()
    {
    	return $this->hasOne('App\Models\Transaksi','id_jenis_transaksi','id');
    }

}
