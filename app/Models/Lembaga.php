<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembaga extends Model
{
    use HasFactory;

    protected $table = "lembaga";
    protected $primaryKey = "id";
    protected $fillable = ['nama_lembaga', 'jenis', 'status'];
    public $timestamps = true;

    public function transaksi()
    {
    	return $this->belongsTo('App\Models\Transaksi', 'id_lembaga', 'id');
    }

    public function rekeninglembaga()
    {
    	return $this->hasMany('App\Models\RekeningLembaga','id_lembaga','id');
    }

    public function lembagakhusus()
    {
        return $this->hasMany('App\Models\LembagaKhusus','id_lembaga','id');
    }
}
