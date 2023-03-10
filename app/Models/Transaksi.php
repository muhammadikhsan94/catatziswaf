<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = "transaksi";
    protected $primaryKey = "id";
    protected $fillable = ['no_kuitansi', 'keterangan', 'bukti_transaksi', 'id_users', 'id_donatur', 'id_lembaga', 'id_jenis_transaksi', 'rek_bank'];
    public $timestamps = true;

    public function lembaga()
    {
    	return $this->hasOne('App\Models\Lembaga', 'id', 'id_lembaga');
    }

    public function statustransaksi()
    {
        return $this->hasOne('App\Models\StatusTransaksi', 'id_transaksi', 'id');
    }

    public function donatur()
    {
        return $this->hasOne('App\Models\Donatur', 'id', 'id_donatur');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'id_users');
    }

    public function barang()
    {
        return $this->hasOne('App\Models\Barang','id_transaksi','id');
    }

    public function jenistransaksi()
    {
        return $this->hasOne('App\Models\JenisTransaksi','id','id_jenis_transaksi');
    }

    public function detailtransaksi()
    {
        return $this->hasMany('App\Models\DetailTransaksi','id_transaksi','id');
    }
    
    public function delete()
    {
        // delete all related contacts
        $this->detailtransaksi()->delete();
        $this->statustransaksi()->delete();

        // delete the customer
        return parent::delete();
    }

}
