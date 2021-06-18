<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusTransaksi extends Model
{
    use HasFactory;

    protected $table = "status_transaksi";
    protected $primaryKey = "id";
    protected $fillable = ['id_transaksi', 'manajer_status', 'panzisda_status', 'lazis_status', 'komentar'];
    public $timestamps = false;

    public function transaksi()
    {
    	return $this->belongsTo('App\Models\Transaksi', 'id', 'id_transaksi');
    }
}
