<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class StrukturExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $id;

    function __construct($id) {
        $this->id = $id;
    }

    public function collection()
    {
        if($this->id == NULL) {
            $data = DB::table('transaksi')
                ->join('status_transaksi','transaksi.id','=','status_transaksi.id_transaksi')
                ->where('status_transaksi.panzisda_status','!=',NULL)
                ->join('users','transaksi.id_users','=','users.id')
                ->join('lembaga','transaksi.id_lembaga','=','lembaga.id')
                ->join('wilayah','users.id_wilayah','=','wilayah.id')
                ->join('donatur','donatur.id','=','transaksi.id_donatur')
                ->join('paketzakat','paketzakat.id','=','transaksi.id_paket_zakat')
                ->select('transaksi.no_kuitansi','users.nama as duta','users.no_punggung','wilayah.nama_wilayah','donatur.nama as donatur','lembaga.nama_lembaga','paketzakat.nama_paket_zakat','transaksi.item','transaksi.jumlah')
                ->where('lembaga.kode_lembaga', 'STR')
                ->orderBy('transaksi.id', 'DESC')
                ->get();
        } else {
            $data = DB::table('transaksi')
                ->join('status_transaksi','transaksi.id','=','status_transaksi.id_transaksi')
                ->where('status_transaksi.panzisda_status','!=',NULL)
                ->join('users','transaksi.id_users','=','users.id')
                ->where('users.id_wilayah', $this->id)
                ->join('lembaga','transaksi.id_lembaga','=','lembaga.id')
                ->join('wilayah','users.id_wilayah','=','wilayah.id')
                ->join('donatur','donatur.id','=','transaksi.id_donatur')
                ->join('paketzakat','paketzakat.id','=','transaksi.id_paket_zakat')
                ->select('transaksi.no_kuitansi','users.nama as duta','users.no_punggung','wilayah.nama_wilayah','donatur.nama as donatur','lembaga.nama_lembaga','paketzakat.nama_paket_zakat','transaksi.item','transaksi.jumlah')
                ->where('lembaga.kode_lembaga', 'STR')
                ->orderBy('transaksi.id', 'DESC')
                ->get();
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'Nomor Kuitansi',
            'Nama Duta Zakat',
            'Nomor Punggung',
            'Nama Daerah',
            'Nama Muzakki',
            'Lembaga',
            'Paket Zakat',
            'Jenis Transaksi',
            'Jumlah',
        ];
    }
}
