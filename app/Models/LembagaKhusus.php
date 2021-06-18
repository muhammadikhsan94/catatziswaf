<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LembagaKhusus extends Model
{
    use HasFactory;

    protected $table = 'lembaga_khusus';
    protected $primaryKey = 'id';
    protected $fillable = ['id_lembaga','id_wilayah'];
    public $timestamps = true;

    public function lembaga()
    {
        return $this->belongsTo('App\Models\Lembaga','id','id_lembaga');
    }

    public function wilayah()
    {
        return $this->belongsTo('App\Models\Wilayah','id','id_wilayah');
    }
}
