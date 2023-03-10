<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = "wilayah";
    protected $primaryKey = "id";
    protected $fillable = ['kode_wilayah', 'nama_wilayah', 'target'];
    public $timestamps = true;

    public function user()
    {
    	return $this->belongsTo('App\Models\User', 'id_wilayah', 'id');
    }

    public function lembagakhusus()
    {
        return $this->hasMany('App\Models\LembagaKhusus','id_wilayah','id');
    }
}
