<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donatur extends Model
{
    use HasFactory;

    protected $table = "donatur";
    protected $primaryKey = "id";
    protected $fillable = ['id_donatur', 'nama', 'alamat', 'npwp', 'no_hp', 'email', 'penghasilan', 'tanggungan', 'status_rumah'];
    public $timestamps = true;

    public function transaksi()
    {
    	return $this->belongsTo('App\Models\Transaksi', 'id_donatur', 'id');
    }

    public function perencanaan()
    {
    	return $this->hasMany('App\Models\Perencanaan','id_donatur','id');
    }
}
