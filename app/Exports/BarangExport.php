<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class BarangExport implements FromCollection, WithHeadings
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
                ->join('detail_transaksi AS detail','detail.id_transaksi','=','transaksi.id')
                ->join('status_transaksi','transaksi.id','=','status_transaksi.id_transaksi')
                ->join('users','transaksi.id_users','=','users.id')
                ->join('lembaga','transaksi.id_lembaga','=','lembaga.id')
                ->join('jenis_transaksi As jenis','jenis.id','=','transaksi.id_jenis_transaksi')
                ->join('wilayah','users.id_wilayah','=','wilayah.id')
                ->join('donatur','donatur.id','=','transaksi.id_donatur')
                ->join('paketzakat','paketzakat.id','=','detail.id_paket_zakat')
                ->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
                ->select('transaksi.no_kuitansi','transaksi.tanggal_transfer','users.nama as duta','users.no_punggung','wilayah.nama_wilayah','donatur.nama as donatur','lembaga.nama_lembaga','paketzakat.nama_paket_zakat','jenis.jenis_transaksi','barang.nama_barang',DB::raw('SUM(detail.jumlah) AS jumlah'))
                ->where('status_transaksi.panzisda_status','!=',NULL)
                ->where('transaksi.id_jenis_transaksi', 4)
                ->groupBy('transaksi.no_kuitansi','transaksi.tanggal_transfer','users.nama','users.no_punggung','wilayah.nama_wilayah','donatur.nama','lembaga.nama_lembaga','paketzakat.nama_paket_zakat','jenis.jenis_transaksi','barang.nama_barang')
                ->orderBy('transaksi.tanggal_transfer', 'ASC')
                ->get();
        } else {
            $data = DB::table('transaksi')
                ->join('detail_transaksi AS detail','detail.id_transaksi','=','transaksi.id')
                ->join('status_transaksi','transaksi.id','=','status_transaksi.id_transaksi')
                ->join('users','transaksi.id_users','=','users.id')
                ->join('lembaga','transaksi.id_lembaga','=','lembaga.id')
                ->join('jenis_transaksi As jenis','jenis.id','=','transaksi.id_jenis_transaksi')
                ->join('wilayah','users.id_wilayah','=','wilayah.id')
                ->join('donatur','donatur.id','=','transaksi.id_donatur')
                ->join('paketzakat','paketzakat.id','=','detail.id_paket_zakat')
                ->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
                ->select('transaksi.no_kuitansi','transaksi.tanggal_transfer','users.nama as duta','users.no_punggung','wilayah.nama_wilayah','donatur.nama as donatur','lembaga.nama_lembaga','paketzakat.nama_paket_zakat','jenis.jenis_transaksi','barang.nama_barang',DB::raw('SUM(detail.jumlah) AS jumlah'))
                ->where('status_transaksi.panzisda_status','!=',NULL)
                ->where('transaksi.id_jenis_transaksi', 4)
                ->where('users.id_wilayah', $this->id)
                ->groupBy('transaksi.no_kuitansi','transaksi.tanggal_transfer','users.nama','users.no_punggung','wilayah.nama_wilayah','donatur.nama','lembaga.nama_lembaga','paketzakat.nama_paket_zakat','jenis.jenis_transaksi','barang.nama_barang')
                ->orderBy('transaksi.tanggal_transfer', 'ASC')
                ->get();
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'Nomor Kuitansi',
            'Tgl Transaksi',
            'Nama Duta Zakat',
            'Nomor Punggung',
            'Nama Daerah',
            'Nama Muzakki',
            'Lembaga',
            'Paket Zakat',
            'Jenis Transaksi',
            'Nama Barang',
            'Jumlah',
        ];
    }
}
