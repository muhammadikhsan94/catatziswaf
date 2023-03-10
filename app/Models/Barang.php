<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'id';
    protected $fillable = ['id_transaksi', 'nama_barang'];
    public $timestamps = true;

    public function transaksi()
    {
    	return $this->belongsTo('App\Models\Transaksi','id','id_transaksi');
    }
}
