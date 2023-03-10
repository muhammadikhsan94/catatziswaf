<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\PaketZakat;
use App\Models\Lembaga;
use App\Models\Donatur;
use App\Models\StatusTransaksi;
use App\Models\Barang;
use App\Models\Jabatan;
use App\Models\Group;
use App\Models\Perencanaan;
use App\Models\Role;
use App\Models\DetailTransaksi;
use App\Models\Distribusi;
use App\Models\JenisTransaksi;
use App\Models\RekeningLembaga;
use App\Models\Wilayah;
use DataTables;
use Auth;
use DB;
use Session;
use App\Imports\UserImport;
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class LazisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('lazis');

        $this->user_duta    = DB::table('users')
                            ->join('role','role.id_users','=','users.id')
                            ->join('group','group.id','=','role.id_group')
                            ->select('users.*', 'role.id_jabatan', 'role.id_atasan', 'role.id_group', 'group.target')
                            ->where('role.id_jabatan', 5)
                            ->get();

        $this->user_manajer     = DB::table('users')
                                ->join('role','role.id_users','=','users.id')
                                ->select('users.*', 'role.id_jabatan', 'role.id_atasan')
                                ->where('role.id_jabatan', 4)
                                ->get();

        $this->user_manajerarea  = DB::table('users')
                                ->join('role','role.id_users','=','users.id')
                                ->select('users.*', 'role.id_jabatan', 'role.id_atasan')
                                ->where('role.id_jabatan', 3)
                                ->get();

        $this->user_panzisda  = DB::table('users')
                                ->join('role','role.id_users','=','users.id')
                                ->select('users.*', 'role.id_jabatan', 'role.id_atasan')
                                ->where('role.id_jabatan', 2)
                                ->get();

        $this->user_panziswil       = DB::table('users')
                                ->join('role','role.id_users','=','users.id')
                                ->select('users.*', 'role.id_jabatan', 'role.id_atasan')
                                ->where('role.id_jabatan', 1)
                                ->get();

        $this->user         = DB::table('users')
                            ->join('role','role.id_users','=','users.id')
                            ->leftJoin('wilayah','wilayah.id','=','users.id_wilayah')
                            ->select('users.*','role.id_jabatan', 'wilayah.nama_wilayah')
                            ->where('role.id_jabatan', '!=', 5)
                            ->get();

        $this->user_lazis       = DB::table('users')
                                ->join('role','role.id_users','=','users.id')
                                ->select('users.*', 'role.id_jabatan', 'role.id_atasan')
                                ->where('role.id_jabatan', 6)
                                ->get();
    }

    public function index()
    {

        $data['user']       = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']  = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();

        //Lembaga
        $id_lembaga     = DB::table('users')
                        ->where('users.id', Auth::user()->id)
                        ->join('role','role.id_users','=','users.id')
                        ->where('role.id_jabatan', 6)
                        ->leftJoin('lembaga', 'lembaga.id','=','role.id_lembaga')
                        ->select('lembaga.*')
                        ->first();
        
        if ($id_lembaga->nama_lembaga == 'IZI' or $id_lembaga->nama_lembaga == 'LAZDAI' or $id_lembaga->nama_lembaga == 'DANA MANDIRI') {
            $wilayah        = Wilayah::all();
        } else {
            $wilayah        = Wilayah::where('id', Auth::user()->id_wilayah)->get();
        }

        $tmp1 = [];
        foreach ($wilayah as $item) {
            $dummy['name'] = $item->nama_wilayah;

            $y              = DB::table('users')
                            ->where('users.id_wilayah', $item->id)
                            ->join('role','role.id_users','=','users.id')
                            ->where('role.id_jabatan', 5)
                            ->leftJoin('transaksi','transaksi.id_users','=','users.id')
                            ->leftJoin('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                            ->leftJoin('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
                            ->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as y'))
                            ->where('transaksi.id_lembaga', $id_lembaga->id)
                            ->whereNotNull('status_transaksi.lazis_status')
                            ->first();

            $dummy['y'] = $y->y;
            $tmp1[] = $dummy;
        }
        $data['panzisda'] = $tmp1;

        //paket zakat
        $paketzakat  = DB::table('transaksi')
                        ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                        ->leftJoin('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
                        ->join('paketzakat', 'detail_transaksi.id_paket_zakat','=','paketzakat.id')
                        ->select('paketzakat.nama_paket_zakat as name', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as y'))
                        ->where('status_transaksi.lazis_status', '!=', NULL)
                        ->where('transaksi.id_lembaga', $id_lembaga->id)
                        ->groupBy('name')
                        ->get()->toArray();

        $countY = 0;
        foreach ($paketzakat as $item) {
            $countY = $countY + $item->y;
        }

        $tmp3 = [];
        foreach ($paketzakat as $item) {
            $dummyss['name'] = $item->name;
            $dummyss['y'] = $item->y;
            $dummyss['persentase'] = number_format(($item->y / $countY) * 100, 2);
            $tmp3[] = $dummyss;
        }
        $data['paketzakat'] = $tmp3;

        return view('admin.lazis.beranda', compact('data'));

    }

    public function getTransaksi()
    {

        $data['user']       = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']  = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        return view('admin.lazis.transaksi', compact('data'));

    }

    public function getDataTransaksi($id)
    {
        $id_lembaga = Role::with(['User'])->where('id_jabatan', 6)->where('id_users', Auth::user()->id)->pluck('id_lembaga');

        if ($id == 0) {
            $transaksi      = DB::table('transaksi')
                        ->join('lembaga', 'transaksi.id_lembaga','=','lembaga.id')
                        ->join('users', 'transaksi.id_users','=','users.id')
                        ->join('wilayah','wilayah.id','=','users.id_wilayah')
                        ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                        ->join('jenis_transaksi','jenis_transaksi.id','=','transaksi.id_jenis_transaksi')
                        ->join('paketzakat','detail_transaksi.id_paket_zakat','=','paketzakat.id')
                        ->join('donatur','transaksi.id_donatur','=','donatur.id')
                        ->join('status_transaksi', 'transaksi.id','=','status_transaksi.id_transaksi')
                        ->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
                        ->select('transaksi.id', 'wilayah.nama_wilayah', 'status_transaksi.komentar', 'transaksi.tanggal_transfer', 'donatur.nama as donatur', 'users.nama as user', 'jenis_transaksi.jenis_transaksi', 'lembaga.nama_lembaga as lembaga', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'), DB::raw('group_concat(paketzakat.nama_paket_zakat SEPARATOR ", ") as paket'), 'status_transaksi.panzisda_status', 'status_transaksi.lazis_status', 'status_transaksi.updated_at as update', 'status_transaksi.manajer_status')
                        ->where('transaksi.id_lembaga', $id_lembaga)
                        ->orderBy('transaksi.id', 'DESC')
                        ->groupBy('transaksi.id','wilayah.nama_wilayah','donatur.nama','users.nama','jenis_transaksi.jenis_transaksi','lembaga.nama_lembaga', 'status_transaksi.panzisda_status', 'transaksi.tanggal_transfer', 'status_transaksi.lazis_status', 'status_transaksi.komentar', 'status_transaksi.updated_at', 'status_transaksi.manajer_status')
                        ->get();
        } else if ($id == 1) {
            $transaksi      = DB::table('transaksi')
                        ->join('lembaga', 'transaksi.id_lembaga','=','lembaga.id')
                        ->join('users', 'transaksi.id_users','=','users.id')
                        ->join('wilayah','wilayah.id','=','users.id_wilayah')
                        ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                        ->join('jenis_transaksi','jenis_transaksi.id','=','transaksi.id_jenis_transaksi')
                        ->join('paketzakat','detail_transaksi.id_paket_zakat','=','paketzakat.id')
                        ->join('donatur','transaksi.id_donatur','=','donatur.id')
                        ->join('status_transaksi', 'transaksi.id','=','status_transaksi.id_transaksi')
                        ->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
                        ->select('transaksi.id', 'wilayah.nama_wilayah', 'status_transaksi.komentar', 'transaksi.tanggal_transfer', 'donatur.nama as donatur', 'users.nama as user', 'jenis_transaksi.jenis_transaksi', 'lembaga.nama_lembaga as lembaga', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'), DB::raw('group_concat(paketzakat.nama_paket_zakat SEPARATOR ", ") as paket'), 'status_transaksi.panzisda_status', 'status_transaksi.lazis_status', 'status_transaksi.updated_at as update', 'status_transaksi.manajer_status')
                        ->where('transaksi.id_lembaga', $id_lembaga)
                        ->where('status_transaksi.lazis_status', '!=', NULL)
                        ->orderBy('transaksi.id', 'DESC')
                        ->groupBy('transaksi.id','wilayah.nama_wilayah','donatur.nama','users.nama','jenis_transaksi.jenis_transaksi','lembaga.nama_lembaga', 'status_transaksi.panzisda_status', 'transaksi.tanggal_transfer', 'status_transaksi.lazis_status', 'status_transaksi.komentar', 'status_transaksi.updated_at', 'status_transaksi.manajer_status')
                        ->get();
        } else if ($id == 2) {
            $transaksi      = DB::table('transaksi')
                        ->join('lembaga', 'transaksi.id_lembaga','=','lembaga.id')
                        ->join('users', 'transaksi.id_users','=','users.id')
                        ->join('wilayah','wilayah.id','=','users.id_wilayah')
                        ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                        ->join('jenis_transaksi','jenis_transaksi.id','=','transaksi.id_jenis_transaksi')
                        ->join('paketzakat','detail_transaksi.id_paket_zakat','=','paketzakat.id')
                        ->join('donatur','transaksi.id_donatur','=','donatur.id')
                        ->join('status_transaksi', 'transaksi.id','=','status_transaksi.id_transaksi')
                        ->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
                        ->select('transaksi.id', 'wilayah.nama_wilayah', 'status_transaksi.komentar', 'transaksi.tanggal_transfer', 'donatur.nama as donatur', 'users.nama as user', 'jenis_transaksi.jenis_transaksi', 'lembaga.nama_lembaga as lembaga', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'), DB::raw('group_concat(paketzakat.nama_paket_zakat SEPARATOR ", ") as paket'), 'status_transaksi.panzisda_status', 'status_transaksi.lazis_status', 'status_transaksi.updated_at as update', 'status_transaksi.manajer_status')
                        ->where('transaksi.id_lembaga', $id_lembaga)
                        ->where('status_transaksi.komentar', '!=', NULL)
                        ->where('status_transaksi.updated_at', NULL)
                        ->orderBy('transaksi.id', 'DESC')
                        ->groupBy('transaksi.id','wilayah.nama_wilayah','donatur.nama','users.nama','jenis_transaksi.jenis_transaksi','lembaga.nama_lembaga', 'status_transaksi.panzisda_status', 'transaksi.tanggal_transfer', 'status_transaksi.lazis_status', 'status_transaksi.komentar', 'status_transaksi.updated_at', 'status_transaksi.manajer_status')
                        ->get();
        } else if ($id == 3) {
            $transaksi      = DB::table('transaksi')
                        ->join('lembaga', 'transaksi.id_lembaga','=','lembaga.id')
                        ->join('users', 'transaksi.id_users','=','users.id')
                        ->join('wilayah','wilayah.id','=','users.id_wilayah')
                        ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                        ->join('jenis_transaksi','jenis_transaksi.id','=','transaksi.id_jenis_transaksi')
                        ->join('paketzakat','detail_transaksi.id_paket_zakat','=','paketzakat.id')
                        ->join('donatur','transaksi.id_donatur','=','donatur.id')
                        ->join('status_transaksi', 'transaksi.id','=','status_transaksi.id_transaksi')
                        ->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
                        ->select('transaksi.id', 'wilayah.nama_wilayah', 'status_transaksi.komentar', 'transaksi.tanggal_transfer', 'donatur.nama as donatur', 'users.nama as user', 'jenis_transaksi.jenis_transaksi', 'lembaga.nama_lembaga as lembaga', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'), DB::raw('group_concat(paketzakat.nama_paket_zakat SEPARATOR ", ") as paket'), 'status_transaksi.panzisda_status', 'status_transaksi.lazis_status', 'status_transaksi.updated_at as update', 'status_transaksi.manajer_status')
                        ->where('transaksi.id_lembaga', $id_lembaga)
                        ->where('status_transaksi.manajer_status', '!=', NULL)
                        ->where('status_transaksi.panzisda_status', '!=', NULL)
                        ->where('status_transaksi.lazis_status', NULL)
                        ->orderBy('transaksi.id', 'DESC')
                        ->groupBy('transaksi.id','wilayah.nama_wilayah','donatur.nama','users.nama','jenis_transaksi.jenis_transaksi','lembaga.nama_lembaga', 'status_transaksi.panzisda_status', 'transaksi.tanggal_transfer', 'status_transaksi.lazis_status', 'status_transaksi.komentar', 'status_transaksi.updated_at', 'status_transaksi.manajer_status')
                        ->get();
        } else if ($id == 4) {
            $transaksi      = DB::table('transaksi')
                        ->join('lembaga', 'transaksi.id_lembaga','=','lembaga.id')
                        ->join('users', 'transaksi.id_users','=','users.id')
                        ->join('wilayah','wilayah.id','=','users.id_wilayah')
                        ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                        ->join('jenis_transaksi','jenis_transaksi.id','=','transaksi.id_jenis_transaksi')
                        ->join('paketzakat','detail_transaksi.id_paket_zakat','=','paketzakat.id')
                        ->join('donatur','transaksi.id_donatur','=','donatur.id')
                        ->join('status_transaksi', 'transaksi.id','=','status_transaksi.id_transaksi')
                        ->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
                        ->select('transaksi.id', 'wilayah.nama_wilayah', 'status_transaksi.komentar', 'transaksi.tanggal_transfer', 'donatur.nama as donatur', 'users.nama as user', 'jenis_transaksi.jenis_transaksi', 'lembaga.nama_lembaga as lembaga', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'), DB::raw('group_concat(paketzakat.nama_paket_zakat SEPARATOR ", ") as paket'), 'status_transaksi.panzisda_status', 'status_transaksi.lazis_status', 'status_transaksi.updated_at as update', 'status_transaksi.manajer_status')
                        ->where('transaksi.id_lembaga', $id_lembaga)
                        ->where('status_transaksi.manajer_status', '!=', NULL)
                        ->where('status_transaksi.panzisda_status', NULL)
                        ->orderBy('transaksi.id', 'DESC')
                        ->groupBy('transaksi.id','wilayah.nama_wilayah','donatur.nama','users.nama','jenis_transaksi.jenis_transaksi','lembaga.nama_lembaga', 'status_transaksi.panzisda_status', 'transaksi.tanggal_transfer', 'status_transaksi.lazis_status', 'status_transaksi.komentar', 'status_transaksi.updated_at', 'status_transaksi.manajer_status')
                        ->get();
        } else {
            $transaksi      = DB::table('transaksi')
                        ->join('lembaga', 'transaksi.id_lembaga','=','lembaga.id')
                        ->join('users', 'transaksi.id_users','=','users.id')
                        ->join('wilayah','wilayah.id','=','users.id_wilayah')
                        ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                        ->join('jenis_transaksi','jenis_transaksi.id','=','transaksi.id_jenis_transaksi')
                        ->join('paketzakat','detail_transaksi.id_paket_zakat','=','paketzakat.id')
                        ->join('donatur','transaksi.id_donatur','=','donatur.id')
                        ->join('status_transaksi', 'transaksi.id','=','status_transaksi.id_transaksi')
                        ->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
                        ->select('transaksi.id', 'wilayah.nama_wilayah', 'status_transaksi.komentar', 'transaksi.tanggal_transfer', 'donatur.nama as donatur', 'users.nama as user', 'jenis_transaksi.jenis_transaksi', 'lembaga.nama_lembaga as lembaga', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'), DB::raw('group_concat(paketzakat.nama_paket_zakat SEPARATOR ", ") as paket'), 'status_transaksi.panzisda_status', 'status_transaksi.lazis_status', 'status_transaksi.updated_at as update', 'status_transaksi.manajer_status')
                        ->where('transaksi.id_lembaga', $id_lembaga)
                        ->where('status_transaksi.manajer_status', NULL)
                        ->orWhere('status_transaksi.updated_at', '!=', NULL)
                        ->orderBy('transaksi.id', 'DESC')
                        ->groupBy('transaksi.id','wilayah.nama_wilayah','donatur.nama','users.nama','jenis_transaksi.jenis_transaksi','lembaga.nama_lembaga', 'status_transaksi.panzisda_status', 'transaksi.tanggal_transfer', 'status_transaksi.lazis_status', 'status_transaksi.komentar', 'status_transaksi.updated_at', 'status_transaksi.manajer_status')
                        ->get();
        }
        
        return DataTables::of($transaksi)
        ->addIndexColumn()
        ->addColumn('aksi', function($transaksi) {
            if ($transaksi->panzisda_status == NULL) {
                $button = '<center><button type="button" name="detail" id="'.$transaksi->id.'" class="detail btn btn-secondary btn-xs" disabled>VERIFIKASI</button></center>';
            } else {
                $button = '<center><button type="button" name="detail" id="'.$transaksi->id.'" class="detail btn btn-secondary btn-xs">VERIFIKASI</button></center>';
            }
            return $button;
        })
        ->editColumn('lazis_status', function($transaksi){
            if (($transaksi->manajer_status == null and $transaksi->komentar == null) or $transaksi->update != null) {
                $text = '<b style="color:brown;">TUNGGU PROSES MG</b>';
            } else if ($transaksi->manajer_status != NULL and $transaksi->panzisda_status == null and $transaksi->lazis_status == null) {
                $text = '<b style="color:brown;">TUNGGU PROSES ZISDA</b>';
            } else if ($transaksi->manajer_status != NULL and $transaksi->panzisda_status != null and $transaksi->lazis_status == null) {
                $text = '<b style="color:brown;">TUNGGU PROSES LAZ</b>';
            } else if ($transaksi->manajer_status == null and $transaksi->komentar != null) {
                $text = '<b style="color:red;">TIDAK VALID</b>';
            } else if ($transaksi->lazis_status != NULL) {
                $text = '<b style="color:green;">VALID</b>';
            }
            return $text;
        })
        ->editColumn('lembaga', function($transaksi){
            return strtoupper($transaksi->lembaga);
        })
        ->editColumn('tanggal_transfer', function($transaksi){
            return date('d/m/Y', strtotime($transaksi->tanggal_transfer));
        })
        ->editColumn('jenis_transaksi', function($transaksi){
            return strtoupper($transaksi->jenis_transaksi);
        })
        ->editColumn('jumlah', function($transaksi){
            return format_uang_with_rp($transaksi->jumlah);
        })
        ->rawColumns(['lazis_status','aksi'])
        ->make(true);
    }

    public function detailTransaksi($id)
    {
        $data =             DB::table('transaksi')
                            ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                            ->join('paketzakat','paketzakat.id','=','detail_transaksi.id_paket_zakat')
                            ->join('lembaga','lembaga.id','=','transaksi.id_lembaga')
                            ->join('jenis_transaksi','jenis_transaksi.id','=','transaksi.id_jenis_transaksi')
                            ->join('status_transaksi','transaksi.id','=','status_transaksi.id_transaksi')
                            ->join('donatur','donatur.id','=','transaksi.id_donatur')
                            ->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
                            ->select('transaksi.id', 'donatur.nama as donatur', 'lembaga.nama_lembaga as lembaga', 'barang.nama_barang', 'transaksi.rek_bank', 'transaksi.bukti_transaksi', 'transaksi.keterangan', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'), DB::raw('group_concat(paketzakat.nama_paket_zakat SEPARATOR ", ") as paket'), DB::raw('DATE_FORMAT(transaksi.tanggal_transfer, "%d-%m-%Y") as tanggal_transfer'), 'status_transaksi.panzisda_status','jenis_transaksi.jenis_transaksi', DB::raw('COUNT(paketzakat.nama_paket_zakat) as jumlah_paket'), 'status_transaksi.komentar')
                            ->where('transaksi.id', $id)
                            ->groupBy('transaksi.id','donatur.nama','jenis_transaksi.jenis_transaksi','lembaga.nama_lembaga','barang.nama_barang','transaksi.rek_bank','transaksi.bukti_transaksi','transaksi.keterangan', 'transaksi.tanggal_transfer', 'status_transaksi.panzisda_status', 'status_transaksi.komentar')
                            ->first();

        $data->detail =     DB::table('transaksi')
                            ->where('transaksi.id', $data->id)
                            ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                            ->join('paketzakat','detail_transaksi.id_paket_zakat','=','paketzakat.id')
                            ->select('paketzakat.nama_paket_zakat', 'detail_transaksi.jumlah')
                            ->get();

        return json_encode($data);
    }

    public function getStatus($id)
    {
        $data = Transaksi::find($id);

        return json_encode($data);
    }

    public function updateStatus(Request $request)
    {
        if ($request->setujui == 'OK') {
            $status = StatusTransaksi::where('id_transaksi', $request->idTrx1)->first();
            $status->lazis_status = Auth::user()->id;
            $status->updated_at = null;
            $status->komentar = null;
            $status->save();
        } else {
            $status = StatusTransaksi::where('id_transaksi', $request->idTrx2)->first();
            $status->manajer_status = null;
            $status->panzisda_status = null;
            $status->lazis_status = null;
            $status->updated_at = null;
            $status->komentar = $request->komentar;
            $status->save();
        }
    }

    public function getLaporanValidasi()
    {
        $data['user']               = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();      
        return view('admin.lazis.laporan_validasi', compact('data'));
    }

    public function getDataLaporanValidasi()
    {
        $lembaga     = DB::table('users')
                        ->where('users.id', Auth::user()->id)
                        ->join('role','role.id_users','=','users.id')
                        ->where('role.id_jabatan', 6)
                        ->leftJoin('lembaga', 'lembaga.id','=','role.id_lembaga')
                        ->select('lembaga.*')
                        ->first();

        if ($lembaga->nama_lembaga == 'IZI' or $lembaga->nama_lembaga == 'LAZDAI' or $lembaga->nama_lembaga == 'DANA MANDIRI') {
            $wilayah        = Wilayah::all();
        } else {
            $wilayah        = Wilayah::where('id', Auth::user()->id_wilayah)->get();
        }

        $dummy   = [];
        foreach($wilayah as $value) {
            $target     = DB::table('users')
                        ->where('users.deleted_at', NULL)
                        ->where('users.id_wilayah', $value->id)
                        ->join('role','role.id_users','=','users.id')
                        ->where('role.id_jabatan', 5)
                        ->join('group','group.id','=','role.id_group')
                        ->select(DB::raw('CAST(SUM(group.target) as UNSIGNED) as target'))
                        ->first();

            $transaksi  = DB::table('users')
                        ->where('users.deleted_at', NULL)
                        ->where('users.id_wilayah', $value->id)
                        ->join('role','role.id_users','=','users.id')
                        ->where('role.id_jabatan', 5)
                        ->join('transaksi','transaksi.id_users','=','users.id')
                        ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                        ->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
                        ->where('transaksi.id_lembaga', $lembaga->id)
                        ->select('status_transaksi.manajer_status', 'status_transaksi.panzisda_status', 'status_transaksi.lazis_status', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
                        ->groupBy('status_transaksi.manajer_status', 'status_transaksi.panzisda_status', 'status_transaksi.lazis_status')
                        ->get();
            
            $tmp['nama_wilayah'] = $value->nama_wilayah;
            $tmp['target']      = ($target->target != 0) ? $target->target : 0;

            $valid_mg = 0;
            $valid_pz = 0;
            $valid_lz = 0;
            $total = 0;
            foreach($transaksi as $item) {
                if ($item->manajer_status != NULL and $item->panzisda_status != NULL and $item->lazis_status != NULL) {
                    $valid_lz = $valid_lz + $item->jumlah;
                    $valid_pz = $valid_pz + $item->jumlah;
                    $valid_mg = $valid_mg + $item->jumlah;
                } else if ($item->manajer_status != NULL and $item->panzisda_status != NULL) {
                    $valid_pz = $valid_pz + $item->jumlah;
                    $valid_mg = $valid_mg + $item->jumlah;
                } else if ($item->manajer_status != NULL) {
                    $valid_mg = $valid_mg + $item->jumlah;
                }

                $total = $total + $item->jumlah;
            }
            
            $tmp['total']       = $total;
            $tmp['valid_mg']    = $valid_mg;
            $tmp['valid_pz']    = $valid_pz;
            $tmp['valid_lz']    = $valid_lz;
            $tmp['persentase']  = ($valid_lz != 0) ? number_format(($valid_lz / $target->target) * 100, 2) : 0;
            $dummy[]            = $tmp;
        }
        $data = collect($dummy);

        $data = $data->sortByDesc('total');

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
    
    public function getLaporanRealisasiPaketZiswaf()
    {
        $data['user']               = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first(); 
        $data['lembaga']            = DB::table('lembaga')->join('role','role.id_lembaga','=','lembaga.id')->select('lembaga.nama_lembaga')->where('role.id_users', Auth::user()->id)->where('role.id_lembaga', '!=', NULL)->first();

        if ($data['lembaga']->nama_lembaga == 'izi' or $data['lembaga']->nama_lembaga == 'lazdai' or $data['lembaga']->nama_lembaga == 'dana mandiri') {
            $data['wilayah']         = Wilayah::all();
        } else {
            $data['wilayah']         = Wilayah::where('id', Auth::user()->id_wilayah)->get();
        }
        
        return view('admin.lazis.laporan_realisasi_paket_ziswaf', compact('data'));
    }

    public function getDataLaporanRealisasiPaketZiswaf($id)
    {
        $paket      = PaketZakat::all();
        
        $lembaga     = DB::table('users')
                        ->where('users.id', Auth::user()->id)
                        ->join('role','role.id_users','=','users.id')
                        ->where('role.id_jabatan', 6)
                        ->leftJoin('lembaga', 'lembaga.id','=','role.id_lembaga')
                        ->select('lembaga.*')
                        ->first();

        if($id == 0) {
            if ($lembaga->nama_lembaga == 'IZI' or $lembaga->nama_lembaga == 'LAZDAI' or $lembaga->nama_lembaga == 'DANA MANDIRI') {
                $wilayah        = Wilayah::all();
            } else {
                $wilayah        = Wilayah::where('id', Auth::user()->id_wilayah)->get();
            }

            $temp = [];
            foreach($wilayah as $item1) {
                $dummy['wilayah'] = $item1->nama_wilayah;

                foreach($paket as $item2) {
                    $transaksi  = DB::table('transaksi')
                                ->where('transaksi.id_lembaga', $lembaga->id)
                                ->join('users','users.id','=','transaksi.id_users')
                                ->where('users.id_wilayah', $item1->id)
                                ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                                ->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
                                ->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
                                ->where('detail_transaksi.id_paket_zakat', $item2->id)
                                ->where('status_transaksi.lazis_status', '!=', NULL)
                                ->first();
                    if ($transaksi->jumlah == NULL) {
                        $transaksi->jumlah = 0;
                    }

                    $dummy['paket'] = $item2->nama_paket_zakat;
                    $dummy['jumlah'] = $transaksi->jumlah;
                    $temp[] = $dummy;
                }
            }
        } else {
            if ($lembaga->nama_lembaga == 'IZI' or $lembaga->nama_lembaga == 'LAZDAI' or $lembaga->nama_lembaga == 'DANA MANDIRI') {
                $wilayah    = Wilayah::where('id', $id)->get();
            } else {
                $wilayah    = Wilayah::where('id', Auth::user()->id_wilayah)->get();
            }

            $temp = [];
            foreach($wilayah as $item1) {
                $dummy['wilayah'] = $item1->nama_wilayah;

                foreach($paket as $item2) {
                    $transaksi  = DB::table('transaksi')
                                ->where('transaksi.id_lembaga', $lembaga->id)
                                ->leftJoin('users','users.id','=','transaksi.id_users')
                                ->where('users.id_wilayah', $item1->id)
                                ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                                ->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
                                ->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
                                ->where('detail_transaksi.id_paket_zakat', $item2->id)
                                ->where('status_transaksi.lazis_status', '!=', NULL)
                                ->first();
                    if ($transaksi->jumlah == NULL) {
                        $transaksi->jumlah = 0;
                    }

                    $dummy['paket'] = $item2->nama_paket_zakat;
                    $dummy['jumlah'] = $transaksi->jumlah;
                    $temp[] = $dummy;
                }
            }
        }
        $data = $temp;

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
    
    public function getLaporanDistribusi()
    {
        $data['user']               = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();      
        $data['lembaga']            = DB::table('lembaga')->join('role','role.id_lembaga','=','lembaga.id')->select('lembaga.nama_lembaga')->where('role.id_users', Auth::user()->id)->where('role.id_jabatan', 6)->first();

        return view('admin.lazis.laporan_distribusi', compact('data'));
    }

    public function getDataLaporanDistribusi()
    {
        $paket      = PaketZakat::all();
        $lembaga    = Role::where('id_users', Auth::user()->id)->where('id_jabatan', 6)->pluck('id_lembaga');

        $temp = [];
        foreach($paket as $item1) {
            $dummy['paket'] = $item1->nama_paket_zakat;
            $jumlah     = DB::table('transaksi')
                        ->where('transaksi.id_lembaga', $lembaga)
                        ->leftJoin('users','users.id','=','transaksi.id_users')
                        ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                        ->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
                        ->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
                        ->where('detail_transaksi.id_paket_zakat', $item1->id)
                        ->where('status_transaksi.lazis_status', '!=', NULL)
                        ->first();
            
            $distribusi     = Distribusi::where('id_paket_zakat', $item1->id)->get();
            
            foreach($distribusi as $dis) {
                $dummy['panzisnas'] = ($jumlah->jumlah != NULL) ? round(($dis->panzisnas * $jumlah->jumlah) / 100) : 0;
                $dummy['panziswil'] = ($jumlah->jumlah != NULL) ? round(($dis->panziswil * $jumlah->jumlah) / 100) : 0;
                $dummy['panzisda'] = ($jumlah->jumlah != NULL) ? round(($dis->panzisda * $jumlah->jumlah) / 100) : 0;
    
                foreach($lembaga as $item2) {
                    $transaksi  = DB::table('transaksi')
                                ->leftJoin('users','users.id','=','transaksi.id_users')
                                ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                                ->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
                                ->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
                                ->where('detail_transaksi.id_paket_zakat', $item1->id)
                                ->where('transaksi.id_lembaga', $lembaga)
                                ->where('status_transaksi.lazis_status', '!=', NULL)
                                ->first();
                    
                    $dummy['lembaga'] = ($transaksi->jumlah != NULL) ? round(($dis->mitra_strategis * $transaksi->jumlah) / 100) : 0;
                }
            }
            $dummy['jumlah'] = $dummy['panzisnas'] + $dummy['panziswil'] + $dummy['panzisda'] + $dummy['lembaga'];
            $temp[] = $dummy;
        }
        $data = $temp;

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
}
