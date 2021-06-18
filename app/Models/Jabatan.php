<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = "jabatan";
    protected $primaryKey = "id";
    protected $fillable = ['kode_jabatan', 'nama_jabatan'];
    public $timestamps = true;

    public function role()
    {
    	return $this->belongsTo('App\Models\Role', 'id_jabatan', 'id');
    }

}
