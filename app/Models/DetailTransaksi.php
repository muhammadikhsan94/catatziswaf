<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;
    protected $table = "detail_transaksi";
    protected $primaryKey = "id";
    protected $fillable = ['id_transaksi','id_paket_zakat','jumlah'];
    public $timestamps = true;

    public function transaksi()
    {
    	return $this->belongsTo('App\Models\Transaksi','id','id_transaksi');
    }

    public function paketzakat()
    {
    	return $this->hasOne('App\Models\PaketZakat','id','id_paket_zakat');
    }
}
