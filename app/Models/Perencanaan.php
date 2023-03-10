<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perencanaan extends Model
{
    use HasFactory;

    protected $table = "perencanaan";
    protected $primaryKey = "id";
    protected $fillable = ['id_duta', 'id_donatur'];
    public $timestamps = false;

    public function user()
    {
    	return $this->belongsTo('App\Models\User', 'id', 'id_duta');
    }

    public function donatur()
    {
    	return $this->belongsTo('App\Models\Donatur','id','id_donatur');
    }
}
