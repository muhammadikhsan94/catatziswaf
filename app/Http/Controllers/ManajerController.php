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
use Carbon\Carbon;

class ManajerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('manajer');

        $this->user_duta    = DB::table('users')
                            ->join('role','role.id_users','=','users.id')
                            ->join('group','group.id','=','role.id_group')
                            ->select('users.*', 'role.id_jabatan', 'role.id_atasan', 'role.id_group', 'group.target')
                            ->where('role.id_jabatan', 5)
                            ->get();

        $this->user         = DB::table('users')
                            ->join('role','role.id_users','=','users.id')
                            ->leftJoin('wilayah','wilayah.id','=','users.id_wilayah')
                            ->select('users.*','role.id_jabatan', 'wilayah.nama_wilayah')
                            ->where('role.id_jabatan', '!=', 5)
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

        $this->user_lazis       = DB::table('users')
                                ->join('role','role.id_users','=','users.id')
                                ->select('users.*', 'role.id_jabatan', 'role.id_atasan')
                                ->where('role.id_jabatan', 6)
                                ->get();

        $this->user_panziswil       = DB::table('users')
                                ->join('role','role.id_users','=','users.id')
                                ->select('users.*', 'role.id_jabatan', 'role.id_atasan')
                                ->where('role.id_jabatan', 1)
                                ->get();
    }

    public function index()
    {

        $data['user']       = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']  = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();

        $duta               = DB::table('users')
                            ->where('users.id_wilayah', Auth::user()->id_wilayah)
                            ->where('users.deleted_at', NULL)
                            ->join('role','role.id_users','=','users.id')
                            ->where('role.id_atasan', Auth::user()->id)
                            ->where('role.id_jabatan', 5)
                            ->select('users.*')
                            ->get();

        $tmp = [];
        foreach ($duta as $item) {
            $target         = DB::table('users')
                            ->join('role', 'role.id_users','=','users.id')
                            ->where('users.id', $item->id)
                            ->where('role.id_jabatan', 5)
                            ->join('group','group.id','=','role.id_group')
                            ->select('group.target')
                            ->first();

            $y              = DB::table('users')
                            ->join('role', 'role.id_users','=','users.id')
                            ->where('users.id', $item->id)
                            ->where('role.id_jabatan', 5)
                            ->join('transaksi','users.id','=','transaksi.id_users')
                            ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                            ->join('status_transaksi','transaksi.id','=','status_transaksi.id_transaksi')
                            ->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as y'))
                            ->where('status_transaksi.lazis_status', '!=', NULL)
                            ->first();

            $dummy['name'] = $item->nama;
            $dummy['y'] = ($y->y != NULL) ? $y->y : 0;
            $dummy['y1'] = format_uang($dummy['y']);
            $dummy['target'] = ($target->target != NULL) ? $target->target : 0;
            $dummy['targets'] = format_uang($dummy['target']);
            $dummy['persentase'] = ($dummy['y'] != 0) ? number_format(($dummy['y'] / $dummy['target']) * 100, 2) : 0;
            $tmp[] = $dummy;
        }
        $data['duta'] = $tmp;

        //Realisasi
        $target         = DB::table('users')
                        ->join('role','role.id_users','=','users.id')
                        ->where('role.id_jabatan', 5)
                        ->where('role.id_atasan', Auth::user()->id)
                        ->join('group','group.id','=','role.id_group')
                        ->select(DB::raw('CAST(SUM(group.target) as UNSIGNED) as target'))
                        ->get();

        $y              = DB::table('users')
                        ->join('role','role.id_users','=','users.id')
                        ->where('role.id_jabatan', 5)
                        ->where('role.id_atasan', Auth::user()->id)
                        ->leftJoin('transaksi','transaksi.id_users','=','users.id')
                        ->leftJoin('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                        ->leftJoin('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
                        ->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as y'), 'status_transaksi.panzisda_status')
                        ->groupBy('status_transaksi.panzisda_status')
                        ->orderBy('status_transaksi.panzisda_status', 'DESC')
                        ->get();
        
        foreach($target as $value1) {
            $value1->name = Auth::user()->nama;
            
            $sumY = 0;
            foreach($y as $value2) {
                if ($value2->panzisda_status != null) {
                    $sumY = $sumY + $value2->y;
                }
            }

            $value1->y = $sumY;
            $value1->y_ = format_uang($sumY);
            $value1->target_ = format_uang($value1->target);
            $value1->persentase = ($sumY != 0) ? number_format(($sumY / $value1->target) * 100, 2) : 0;
        }
        
        $data['realisasi']        = $target;

        return view('admin.manajer.beranda', compact('data'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTransaksi()
    {
        $data['user']       = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']  = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
        return view('admin.manajer.transaksi', compact('data'));
    }

    public function getuser()
    {
        $data['user']       = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']  = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
        return view('admin.manajer.user', compact('data'));
    }

    public function getDataUser()
    {
        $user = DB::table('users')->join('role','role.id_users','=','users.id')
                ->select('users.*')
                ->where('role.id_atasan', Auth::user()->id)
                ->where('users.id_wilayah', Auth::user()->id_wilayah)
                ->where('role.id_jabatan', 5)
                ->where('users.deleted_at', NULL)
                ->orderBy('users.id','asc')->get();

        return DataTables::of($user)
        ->addIndexColumn()
        ->addColumn('aksi', function($user) {
            $button = '<center><button type="button" name="detail" id="'.$user->id.'" class="detail btn btn-secondary btn-sm">Detail</button></center>';
            return $button;
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function getStatus($id)
    {
        $data = Transaksi::find($id);

        return json_encode($data);
    }

    public function updateStatus(Request $request)
    {
        if ($request->setujui == 'OK') {
            $data = Transaksi::find($request->idTrx1);
            $status = StatusTransaksi::where('id_transaksi', $data->id)->first();
            $status->manajer_status = Auth::user()->id;
            $status->updated_at = null;
            $status->komentar = null;
            $status->save();
        } else {
            $data = Transaksi::find($request->idTrx2);
            $status = StatusTransaksi::where('id_transaksi', $data->id)->first();
            $status->manajer_status = null;
            $status->updated_at = null;
            $status->komentar = $request->komentar;
            $status->save();
        }

        return response()->json(['success' => 'update success stored!']);
    }

    public function getDataTransaksi()
    {
        $user = DB::table('users')->join('role','role.id_users','=','users.id')->select('users.id')->where('role.id_jabatan', 5)->where('role.id_atasan', Auth::user()->id)->pluck('users.id')->toArray();
        $transaksi      = DB::table('transaksi')
                        ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                        ->join('jenis_transaksi','jenis_transaksi.id','=','transaksi.id_jenis_transaksi')
                        ->join('lembaga', 'transaksi.id_lembaga','=','lembaga.id')
                        ->join('users', 'transaksi.id_users','=','users.id')
                        ->join('paketzakat','detail_transaksi.id_paket_zakat','=','paketzakat.id')
                        ->join('donatur','transaksi.id_donatur','=','donatur.id')
                        ->join('status_transaksi', 'transaksi.id','=','status_transaksi.id_transaksi')
                        ->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
                        ->select('transaksi.id', 'donatur.nama as donatur', 'users.nama as user', 'jenis_transaksi.jenis_transaksi', 'lembaga.nama_lembaga as lembaga', 'status_transaksi.manajer_status as status', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'), DB::raw('group_concat(paketzakat.nama_paket_zakat SEPARATOR ", ") as paket'), 'transaksi.tanggal_transfer', 'status_transaksi.komentar', 'status_transaksi.id as status_id', 'status_transaksi.updated_at as update', 'status_transaksi.lazis_status', 'status_transaksi.panzisda_status')
                        ->where('users.id_wilayah', Auth::user()->id_wilayah)
                        ->whereIn('transaksi.id_users', $user)
                        ->orderBy(DB::raw('status IS NULL'), 'DESC')
                        ->orderBy('transaksi.id', 'desc')
                        ->groupBy('transaksi.id','donatur.nama','users.nama','jenis_transaksi.jenis_transaksi','lembaga.nama_lembaga','status_transaksi.manajer_status', 'transaksi.tanggal_transfer', 'status_transaksi.komentar', 'status_transaksi.updated_at', 'status_transaksi.id', 'status_transaksi.lazis_status', 'status_transaksi.panzisda_status')
                        ->get();

        return DataTables::of($transaksi)
        ->addIndexColumn()
        ->addColumn('aksi', function($transaksi) {
            if ($transaksi->status == NULL) {
                $button = '<center><button type="button" name="detail" id="'.$transaksi->id.'" class="detail btn btn-secondary btn-sm">VERIFIKASI</button></center>';
            } else {
                $button = '<center><button type="button" name="detail" id="'.$transaksi->id.'" class="detail btn btn-secondary btn-sm">DETAIL</button></center>';
            }
            return $button;
        })
        ->editColumn('status', function($transaksi){
            if (($transaksi->status == null and $transaksi->komentar == null) or $transaksi->update != null) {
                $text = '<b style="color:brown;">TUNGGU PROSES MG</b>';
            } else if ($transaksi->status != NULL and $transaksi->panzisda_status == null and $transaksi->lazis_status == null) {
                $text = '<b style="color:brown;">TUNGGU PROSES ZISDA</b>';
            } else if ($transaksi->status != NULL and $transaksi->panzisda_status != null and $transaksi->lazis_status == null) {
                $text = '<b style="color:brown;">TUNGGU PROSES LAZ</b>';
            } else if ($transaksi->status == null and $transaksi->komentar != null) {
                $text = '<b style="color:red;">TIDAK VALID</b>';
            } else if ($transaksi->lazis_status != NULL) {
                $text = '<b style="color:green;">VALID</b>';
            }
            return $text;
        })
        ->editColumn('jumlah', function($transaksi){
            return format_uang_with_rp($transaksi->jumlah);
        })
        ->editColumn('lembaga', function($transaksi){
            return strtoupper($transaksi->lembaga);
        })
        ->editColumn('jenis_transaksi', function($transaksi){
            return strtoupper($transaksi->jenis_transaksi);
        })
        ->editColumn('tanggal_transfer', function($transaksi){
            return date('d/m/Y', strtotime($transaksi->tanggal_transfer));
        })
        ->rawColumns(['status','aksi'])
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

    public function detailUser($id)
    {
        $user = DB::table('users')->join('role','role.id_users','=','users.id')
                ->join('jabatan','role.id_jabatan','=','jabatan.id')
                ->join('wilayah','users.id_wilayah','=','wilayah.id')
                ->select('users.*','role.id_group','jabatan.nama_jabatan','wilayah.nama_wilayah')
                ->where('users.id', $id)
                ->where('role.id_jabatan', 5)
                ->first();

        return json_encode($user);
    }

    public function editProfil()
    {
        $data['user']       = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']  = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
        $data['editUser'] = User::find(Auth::user()->id);

        return view('admin.manajer.profil', compact('data'));
    }

    public function updateProfil(Request $request)
    {
        $user = User::find($request->id);
        $user->nama = $request->nama;
        $user->alamat = $request->alamat;
        $user->npwp = $request->npwp;
        $user->no_hp = $request->no_hp;
        $user->email = $request->email;

        if($request->new_password != NULL or $request->new_password != '') {
            $user->password = Hash::make($request->new_password);
        } else {
            $user->password = $request->password;
        }
        $user->save();
    }

    public function getDutaDonatur()
    {
        $data['user']       = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']  = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
        return view('admin.manajer.asduta.donatur', compact('data'));
    }

    public function getDataDonaturPerencanaan()
    {
        $donatur        = Donatur::orderBy('nama', 'ASC')->get();

        return DataTables::of($donatur)
        ->addIndexColumn()
        ->addColumn('aksi', function($donatur) {
            $button = '<center><button type="button" name="detail" id="'.$donatur->id.'" class="detail btn btn-secondary btn-sm">Detail</button>&nbsp;<button type="button" name="rencana" id="'.$donatur->id.'" class="rencana btn btn-info btn-sm"><i class="fa fa-plus"></i></button></center>';
            return $button;
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function createPlan($id)
    {
        $data = Donatur::find($id);

        return json_encode($data);
    }

    public function savePlan(Request $request)
    {
        $rencana = new Perencanaan();
        $rencana->id_duta = Auth::user()->id;
        $rencana->id_donatur = $request->id_donatur;
        $rencana->save();

        return response()->json(['success' => 'update success stored!']);
    }

    public function deletePlan($id)
    {
        DB::table('perencanaan')->where('id',$id)->delete();
        return redirect()->back();
    }

    public function getLaporan()
    {
        $data['user']               = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();      
        return view('admin.manajer.laporan', compact('data'));
    }

    public function getDataLaporanRealisasi()
    {
        $datas =     DB::table('transaksi')->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                    ->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
                    ->rightJoin('users','users.id','=','transaksi.id_users')
                    ->where('users.id_wilayah', Auth::user()->id_wilayah)
                    ->where('users.deleted_at', NULL)
                    ->join('role','role.id_users','=','users.id')
                    ->where('role.id_jabatan', 5)
                    ->where('role.id_atasan', Auth::user()->id)
                    ->join('group','group.id','=','role.id_group')
                    ->join('wilayah','wilayah.id','=','users.id_wilayah')
                    ->select('users.nama', 'users.no_punggung', 'group.target', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as realisasi'), DB::raw('ROUND(CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) / group.target * 100, 2) as persentase'),'status_transaksi.manajer_status')
                    ->groupBy('users.nama', 'users.no_punggung', 'group.target', 'status_transaksi.manajer_status')
                    ->orderBy('realisasi', 'DESC')
                    ->get();

        $data = [];
        foreach($datas as $item) {
            if ($item->manajer_status == NULL and $item->realisasi == NULL) {
                $data[] = $item;
            } else if ($item->manajer_status != NULL and $item->realisasi != NULL) {
                $data[] = $item;
            }

            if($item->realisasi == NULL) {
                $item->realisasi = 0;
            }
            if($item->persentase == NULL) {
                $item->persentase = 0;
            }
            if($item->target == NULL) {
                $item->target = 0;
            }
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function getDonatur()
    {
        $data['user']               = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();
        return view('admin.manajer.donatur', compact('data'));
    }

    public function simpanDonatur(Request $request)
    {
        $count = count(Donatur::all());
        $count = $count + 1;

        $data = new Donatur();
        $data->id_donatur = str_pad($count, 7, 0, STR_PAD_LEFT).'0';
        $data->nama = $request->nama;
        $data->alamat = $request->alamat;
        $data->npwp = $request->npwp;
        $data->no_hp = $request->no_hp;
        $data->email = $request->email;
        $data->penghasilan = $request->penghasilan;
        $data->tanggungan = $request->tanggungan;
        $data->status_rumah = $request->status_rumah;
        $data->save();
    }

    public function updateDonatur(Request $request)
    {
        // dd($request->all());
        $data = Donatur::find($request->id);

        if (empty($data)) {
            return response()->json(['errors' => [0 => 'Data not found !']]);
        }

        $data->nama = $request->edit_nama;
        $data->alamat = $request->edit_alamat;
        $data->npwp = $request->edit_npwp;
        $data->no_hp = $request->edit_no_hp;
        $data->email = $request->edit_email;
        $data->penghasilan = $request->edit_penghasilan;
        $data->tanggungan = $request->edit_tanggungan;
        $data->status_rumah = $request->edit_status_rumah;
        $data->save();

        return response()->json(['success' => 'berhasil disimpan!']);
    }

    public function editDonatur($id)
    {
        $data = Donatur::find($id);
        return json_encode($data);
    }

    public function detailDonatur($id)
    {
        $data = Donatur::find($id);

        return json_encode($data);
    }

    public function getDataDonatur()
    {
        $donatur        = DB::table('donatur')->leftJoin('transaksi','transaksi.id_donatur','=','donatur.id')->leftJoin('users','users.id','=','transaksi.id_users')->select('donatur.*')->where('transaksi.id_users', Auth::user()->id)->orWhere('users.id_wilayah', Auth::user()->id_wilayah)->orWhereNull('transaksi.id_donatur')->orderBy('donatur.nama', 'ASC')->orderBy('donatur.penghasilan', 'DESC')->get();
        $donatur = $donatur->unique('nama', 'alamat');
        
        return DataTables::of($donatur)
        ->addIndexColumn()
        ->addColumn('aksi', function($donatur) {
            $button = '<center><button type="button" name="edit" id="'.$donatur->id.'" class="edit btn btn-warning btn-xs">Ubah</button>&nbsp;<button type="button" name="detail" id="'.$donatur->id.'" class="detail btn btn-secondary btn-xs">Detail</button></center>';
            return $button;
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

}
