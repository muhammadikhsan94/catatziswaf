<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketZakat extends Model
{
    use HasFactory;

    protected $table = "paketzakat";
    protected $primaryKey = "id";
    protected $fillable = ['nama_paket_zakat'];
    public $timestamps = true;

    public function detailtransaksi()
    {
    	return $this->belongsTo('App\Models\DetailTransaksi', 'id_paket_zakat', 'id');
    }

    public function distribusi()
    {
    	return $this->belongsTo('App\Models\Distribusi','id_paket_zakat','id');
    }

}
