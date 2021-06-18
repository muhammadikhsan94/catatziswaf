<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = "role";
    protected $primaryKey = "id";
    protected $fillable = ['id_users','id_jabatan','id_atasan', 'id_group'];
    public $timestamps = true;

    public function user()
    {
        return $this->hasOne('App\Models\User','id','id_users');
    }

    public function jabatan()
    {
        return $this->belongsTo('App\Models\Jabatan','id','id_jabatan');
    }

    public function group()
    {
        return $this->belongsTo('App\Models\Group','id','id_group');
    }
}
