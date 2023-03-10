<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribusi extends Model
{
    use HasFactory;

    protected $table = "distribusi";
    protected $primaryKey = "id";
    protected $fillable = ['id_paket_zakat','panzisnas','panziswil','panzisda','cabang','mitra_strategis','duta'];
    public $timestamps = true;

    public function paketzakat()
    {
    	return $this->belongsTo('App\Models\PaketZakat','id','id_paket_zakat');
    }
}
