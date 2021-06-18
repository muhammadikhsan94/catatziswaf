<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningLembaga extends Model
{
    use HasFactory;

    protected $table = "rekening_lembaga";
    protected $primaryKey = "id";
    protected $fillable = ['id_lembaga','norek'];
    public $timestamps = true;

    public function lembaga()
    {
    	return $this->belongsTo('App\Models\Lembaga','id','id_lembaga');
    }

}
