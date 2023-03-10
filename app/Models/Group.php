<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = "group";
    protected $primaryKey = "id";
    protected $fillable = ["target"];
    public $timestamps = false;

    public function user()
    {
    	return $this->belongsTo('App\Models\User','id_group','id');
    }

    public function role()
    {
    	return $this->belongsTo('App\Models\Role','id_group','id');
    }
}
