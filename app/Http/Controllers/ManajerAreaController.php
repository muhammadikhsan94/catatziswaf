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

class ManajerAreaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('manajerarea');

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

        $manajer            = DB::table('users')
                            ->join('role','role.id_users','=','users.id')
                            ->select('users.id','users.nama')
                            ->where('role.id_jabatan', 4)
                            ->where('role.id_atasan', Auth::user()->id)
                            ->get();

        $tmp1 = [];
        foreach ($manajer as $item) {
            $dummy['name'] = $item->nama;

            $target         = DB::table('users')
                            ->where('users.deleted_at', NULL)
                            ->join('role','role.id_users','=','users.id')
                            ->where('role.id_atasan', $item->id)
                            ->where('role.id_jabatan', 5)
                            ->join('group','group.id','=','role.id_group')
                            ->select(DB::raw('CAST(SUM(group.target) as UNSIGNED) as target'))
                            ->first();

            $y              = DB::table('users')
                            ->where('users.deleted_at', NULL)
                            ->join('role','role.id_users','=','users.id')
                            ->where('role.id_atasan', $item->id)
                            ->where('role.id_jabatan', 5)
                            ->leftJoin('transaksi','transaksi.id_users','=','users.id')
                            ->leftJoin('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                            ->leftJoin('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
                            ->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as y'))
                            ->where('status_transaksi.lazis_status', '!=', NULL)
                            ->first();

            $dummy['y'] = ($y->y != NULL) ? $y->y : 0;
            $dummy['y_'] = format_uang($y->y);
            $dummy['target'] = ($target->target != NULL) ? $target->target : 0;
            $dummy['drilldown'] = $item->nama;
            $dummy['persentase'] = ($y->y != 0) ? number_format(($y->y / $target->target) * 100, 2) : 0;

            $tmp1[] = $dummy;
            
        }
        $data['manajer'] = $tmp1;

        //DUTA
        $tmp2 = [];
        foreach ($manajer as $value) {
            $dummys['name'] = $value->nama;
            $dummys['id']   = $value->nama;

            $duta           = DB::table('users')
                            ->join('role','role.id_users','=','users.id')
                            ->where('role.id_jabatan', 5)
                            ->where('role.id_atasan', $value->id)
                            ->where('users.id_wilayah', Auth::user()->id_wilayah)
                            ->select('users.*')
                            ->get();

            $tmp3 = [];
            foreach($duta as $value1) {
                $target     = DB::table('users')
                            ->where('users.id', $value1->id)
                            ->join('role', 'role.id_users','=','users.id')
                            ->where('role.id_jabatan', 5)
                            ->join('group','group.id','=','role.id_group')
                            ->select('group.target')
                            ->first();

                $y          = DB::table('users')
                            ->where('users.id', $value1->id)
                            ->join('role', 'role.id_users','=','users.id')
                            ->where('role.id_jabatan', 5)
                            ->join('transaksi','transaksi.id_users','=','users.id')
                            ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                            ->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
                            ->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as y'))
                            ->where('status_transaksi.lazis_status', '!=', NULL)
                            ->first();

                $dummyss['name'] = $value1->nama;
                $dummyss['nama'] = ucwords($value1->nama);
                $dummyss['y'] = ($y->y != NULL) ? $y->y : 0;
                $dummyss['y_'] = format_uang($dummyss['y']);
                $dummyss['target'] = ($target->target != NULL) ? format_uang($target->target) : 0;
                $dummyss['persentase'] = ($dummyss['y'] != 0) ? number_format(($dummyss['y'] / $target->target) * 100, 2) : 0;
                $tmp3[] = $dummyss;
            }
            
            $dummys['data'] = $tmp3;
            $tmp2[] = $dummys;
        }
        $data['duta'] = $tmp2;

        $data['target-manajer'] = 0;
        foreach ($data['manajer'] as $key => $value) {
            if ($value['target'] > $data['target-manajer']) {
                $data['target-manajer'] = (int) str_replace('.', '', $value['target']);
            }
        }

        $data['target-duta'] = 0;
        foreach ($data['duta'] as $key => $value) {
            foreach ($value['data'] as $key => $item) {
                if ($item['target'] > $data['target-duta']) {
                    $data['target-duta'] = (int) str_replace('.', '', $item['target']);
                }
            }
        }

        return view('admin.manajerarea.beranda', compact('data'));
    }

    public function getTransaksi()
    {
        $data['user']       = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']  = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
        return view('admin.manajerarea.transaksi', compact('data'));
    }

    public function getDataTransaksi()
    {
        $user = User::whereIn('id_atasan', function($request){
            $request->select('id')->from('users')->where('id_atasan', Auth::user()->id)->get();
        })->pluck('id')->toArray();

        $transaksi      = DB::table('transaksi')
                        ->join('lembaga', 'transaksi.id_lembaga','=','lembaga.id')
                        ->join('users', 'transaksi.id_users','=','users.id')
                        ->join('paketzakat','transaksi.id_paket_zakat','=','paketzakat.id')
                        ->join('donatur','transaksi.id_donatur','=','donatur.id')
                        ->join('status_transaksi', 'transaksi.id','=','status_transaksi.id_transaksi')
                        ->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
                        ->select('transaksi.*', 'users.nama as user', 'paketzakat.nama_paket_zakat as paket', 'lembaga.nama_lembaga as lembaga','donatur.nama as donatur', 'status_transaksi.spv_status as spv', 'status_transaksi.manajer_status as manajer')
                        ->whereIn('transaksi.id_users', $user)
                        ->orderByRaw('spv ASC')
                        ->orderByRaw('manajer DESC')
                        ->orderBy('transaksi.id', 'desc')
                        ->get()->toArray();

        return DataTables::of($transaksi)
        ->addIndexColumn()
        ->addColumn('aksi', function($transaksi) {
            if ($transaksi->manajer == NULL) {
                $button = '<center><button type="button" name="detail" id="'.$transaksi->id.'" class="detail btn btn-secondary btn-sm">Detail</button>&nbsp;<button type="button" name="validasi" id="'.$transaksi->id.'" class="validasi btn btn-primary btn-sm" disabled>Validasi</button></center>';
            } else if ($transaksi->spv == NULL) {
                $button = '<center><button type="button" name="detail" id="'.$transaksi->id.'" class="detail btn btn-secondary btn-sm">Detail</button>&nbsp;<button type="button" name="validasi" id="'.$transaksi->id.'" class="validasi btn btn-primary btn-sm">Validasi</button></center>';
            } else {
                $button = '<center><button type="button" name="detail" id="'.$transaksi->id.'" class="detail btn btn-secondary btn-sm">Detail</button></center>';
            }
            return $button;
        })
        ->editColumn('jumlah', function($transaksi){
            return format_uang_with_rp($transaksi->jumlah);
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
        $data = Transaksi::find($request->id);
        $status = StatusTransaksi::where('id', $data->id)->first();
        $status->spv_status = Auth::user()->id;
        $status->save();

        return response()->json(['success' => 'update success stored!']);
    }

    public function detailTransaksi($id)
    {
        $data = Transaksi::with(['Donatur', 'Lembaga', 'PaketZakat', 'Barang'])->find($id);

        return json_encode($data);
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
        $data['editUser']   = User::find(Auth::user()->id);

        return view('admin.manajerarea.profil', compact('data'));
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
        return view('admin.manajerarea.asduta.donatur', compact('data'));
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

    public function getLaporanRealisasi()
    {
        $data['user']               = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();      
        return view('admin.manajerarea.laporan_realisasi', compact('data'));
    }

    public function getDataLaporanRealisasi()
    {
        $duta  = DB::table('users')
                ->where('users.deleted_at', NULL)
                ->where('users.id_wilayah', Auth::user()->id_wilayah)
                ->join('role','role.id_users','=','users.id')
                ->whereIn('role.id_atasan', function($query) {
                    $query->select('id_users')->from('role')->where('id_atasan', Auth::user()->id)->where('id_jabatan', 4)->get();
                })
                ->join('group','group.id','=','role.id_group')
                ->where('role.id_jabatan', 5)
                ->select('users.id', 'users.nama', 'users.no_punggung','role.id_atasan as manajer_group', DB::raw('CAST(SUM(group.target) as UNSIGNED) as target'))
                ->groupBy('users.id', 'users.nama', 'users.no_punggung', 'role.id_atasan')
                ->get();

        foreach($duta as $item) {
            $manajer = User::where('id', $item->manajer_group)->first();
            $item->manajer_group = $manajer->nama;

            $realisasi  = DB::table('users')
                        ->where('users.id', $item->id)
                        ->join('role', 'role.id_users','=','users.id')
                        ->where('role.id_jabatan', 5)
                        ->join('transaksi','users.id','=','transaksi.id_users')
                        ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                        ->join('status_transaksi','transaksi.id','=','status_transaksi.id_transaksi')
                        ->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
                        ->where('status_transaksi.lazis_status', '!=', NULL)
                        ->first();

            $item->realisasi = ($realisasi->jumlah != NULL) ? $realisasi->jumlah : 0;
            $item->persentase = ($realisasi->jumlah != NULL) ? number_format(($realisasi->jumlah / $item->target) * 100, 2) : 0;
        }
        $data = $duta->sortbyDesc('realisasi');

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

}
