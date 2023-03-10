<?php

namespace App\Http\Controllers;
// import the storage facade
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
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
use Carbon\Carbon;
use DB;
use Hash;
use PDF;

class DutaZakatController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('dutazakat');
        $this->user     = DB::table('users')
                        ->join('role','role.id_users','=','users.id')
                        ->join('group','group.id','=','role.id_group')
                        ->leftJoin('wilayah','wilayah.id','=','users.id_wilayah')
                        ->select('users.*', 'role.id_jabatan', 'role.id_atasan', 'role.id_group', 'group.target', 'wilayah.nama_wilayah')
                        ->get();

        $this->user_duta        = DB::table('users')
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['user']       = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']  = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
        $data['perhari']    = DB::table('transaksi')->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')->select('transaksi.*','detail_transaksi.id_paket_zakat','detail_transaksi.jumlah')->where('transaksi.created_at', Carbon::today())->where('transaksi.id_users', Auth::user()->id)->get();

        $data['perbulan']   = DB::table('transaksi')->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')->select('transaksi.*','detail_transaksi.id_paket_zakat','detail_transaksi.jumlah')->whereMonth('transaksi.created_at', date('m'))->where('transaksi.id_users', Auth::user()->id)->get();

        $data['pertahun']   = DB::table('transaksi')->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')->select('transaksi.*','detail_transaksi.id_paket_zakat','detail_transaksi.jumlah')->whereYear('transaksi.created_at', date('Y'))->where('transaksi.id_users', Auth::user()->id)->get();

        $data['terkumpul'] = DB::table('transaksi')->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')->select('transaksi.*','detail_transaksi.id_paket_zakat','detail_transaksi.jumlah')->whereYear('transaksi.created_at', date('Y'))->where('transaksi.id_users', Auth::user()->id)->where('status_transaksi.lazis_status', '!=', NULL)->get();

        $data['perencanaan'] = DB::table('perencanaan')->join('donatur', 'donatur.id','=','perencanaan.id_donatur')->select('perencanaan.*', 'donatur.nama')->where('perencanaan.id_duta', Auth::user()->id)->get()->toArray();
        $data['persentase'] = ($data['terkumpul']->sum('jumlah') / $data['user']->target) * 100;
        
        return view('admin.dutazakat.beranda', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tambahTransaksi()
    {
        $data['user']       = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']  = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
        $data['donatur']            = DB::table('donatur')->leftJoin('transaksi','transaksi.id_donatur','=','donatur.id')->leftJoin('users','users.id','=','transaksi.id_users')->select('donatur.id','donatur.nama')->where('users.id', Auth::user()->id)->orWhereNull('users.id_wilayah')->groupBy('donatur.id','donatur.nama')->orderBy('donatur.nama', 'ASC')->get();
        $data['paket']      = PaketZakat::all();
        $data['lembaga']    = DB::table('lembaga')->leftJoin('lembaga_khusus','lembaga_khusus.id_lembaga','=','lembaga.id')->select('lembaga.id', 'lembaga.nama_lembaga')->where('lembaga_khusus.id_wilayah', Auth::user()->id_wilayah)->orWhereNull('lembaga_khusus.id_wilayah')->orderBy('lembaga.id', 'ASC')->get();
        $data['jenis']      = JenisTransaksi::all();
        $data['rekening']   = DB::table('rekening_lembaga')->join('lembaga','lembaga.id','=','rekening_lembaga.id_lembaga')->select('lembaga.nama_lembaga', 'rekening_lembaga.*')->get();

        return view('admin.dutazakat.tambah', compact('data'));
    }

    public function getDonatur()
    {
        $data['user']       = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']  = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
        return view('admin.dutazakat.donatur', compact('data'));
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
        return view('admin.dutazakat.transaksi', compact('data'));
    }

    public function getPerencanaan()
    {
        $data['user']       = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']  = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
        return view('admin.dutazakat.perencanaan', compact('data'));
    }

    public function simpanTransaksi(Request $req)
    {

        $this->validate($req, [
			'bukti_transaksi' => 'required|file|image|mimes:jpeg,png,jpg|max:2048',
		]);

        //Transaksi
        $transaksi                  = new Transaksi();
        //Kuitansi Kode Lembaga
        $getTrx                     = Transaksi::whereYear('created_at', date('Y'))->get()->toArray();
        $countTrx                   = count($getTrx) + 1;
        $transaksi->no_kuitansi     = $countTrx.'/'.Auth::user()->no_punggung.'/'.date('m').'/'.date('Y');

        $transaksi->id_users        = Auth::user()->id;
        $transaksi->id_lembaga      = $req->id_lembaga;
        $transaksi->keterangan      = $req->keterangan;
        $transaksi->id_jenis_transaksi = $req->jenis_transaksi;
        $transaksi->tanggal_transfer    = date("Y-m-d", strtotime($req->tanggal_transfer));
        // str_replace('.', '', $req->jumlah);

        //Bukti Transaksi
        $filename                   = Auth::user()->no_punggung.date('Y').$countTrx.'.'.$req->bukti_transaksi->extension();
        $req->bukti_transaksi->move(public_path().'/bukti/', $filename);
        $transaksi->bukti_transaksi = $filename;

        $nontunai = JenisTransaksi::where('id', '2')->first();
        //Jenis Transaksi
        if ($req->jenis_transaksi != $nontunai->id) {
            $transaksi->rek_bank   = '-';
        } else {
            $transaksi->rek_bank   = $req->rek_bank;
        }

        //Donatur
        if ($req->id_donatur == "tambah") {
            $count                  = count(Donatur::all());
            $countData              = $count + 1;
            
            $donatur                = new Donatur();
            $donatur->id_donatur    = str_pad($countData, 7, 0, STR_PAD_LEFT).'0';
            $donatur->nama          = $req->nama;
            $donatur->no_hp         = $req->no_hp;
            $donatur->alamat        = $req->alamat;
            $donatur->npwp          = $req->npwp;
            $donatur->email         = $req->email;
            $donatur->save();
            $transaksi->id_donatur  = $donatur->id;
        } else {
            $transaksi->id_donatur  = $req->id_donatur;
        }

        //Save
        $transaksi->save();

        //Detail Transaksi
        for ($i=0;$i < count($req->id_paket_zakat);$i++) {
            if($req->id_paket_zakat[$i] != NULL) {
                $detail = new DetailTransaksi();
                $detail->id_transaksi = $transaksi->id;
                $detail->id_paket_zakat = $req->id_paket_zakat[$i];
                $detail->jumlah = str_replace('.', '', $req->jumlah[$i]);
                $detail->save();
            }
        }
        

        //Barang
        $barangx = JenisTransaksi::where('id', '4')->first();
        $barangx = $barang->id ?? null;

        if(ucwords($req->jenis_transaksi) == $barangx) {
            $barang                     = new Barang();
            $barang->id_transaksi       = $transaksi->id;
            $barang->nama_barang        = ucwords($req->nama_barang);
            $barang->save();
        }

        //Status Transaksi
        $status                     = new StatusTransaksi();
        $status->id_transaksi       = $transaksi->id;
        $status->save();
    }
    
    public function cetakBukti($id)
    {
        //Create PDF
        $data = Transaksi::with(['User','Donatur','DetailTransaksi','StatusTransaksi','Barang','DetailTransaksi.PaketZakat','User.Wilayah'])->where('id', $id)->first();
        // dd($data->toArray());
        $total = 0;
        foreach($data->detailtransaksi as $item) {
            $total += $item->jumlah;
        }
        $data->total = $total;
        
        //LOGO
        if ($data->id_lembaga == 1) {
            $data->header = 'assets/logo/header_izi.png';
            $data->ttd = 'assets/logo/izi_bw.png';
        } else if ($data->id_lembaga == 2) {
            $data->header = 'assets/logo/lazdai.png';
            $data->ttd = 'assets/logo/lazdai_bw.png';
        } else {
            $data->header = null;
            $data->ttd = null;
        }
        
        //LEMBAGA
        $data->lembaga->nama_lembaga = strtoupper(strtolower($data->lembaga->nama_lembaga));
        
        $pdf = PDF::loadView('new_kuitansi', compact('data'));
        $pdf->save(public_path('kuitansi/'.$id.'.pdf'));
        return $pdf->download('Kuitansi ZISWAF atas nama '.$data->donatur->nama.'.pdf');
    }

    public function simpanDonatur(Request $request)
    {
        $count = count(Donatur::all());
        $coundData = $count + 1;

        $data = new Donatur();
        $data->id_donatur = str_pad($coundData, 7, 0, STR_PAD_LEFT).'0';
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

    public function editDonatur($id)
    {
        $data = Donatur::find($id);
        return json_encode($data);
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

    public function getDataTransaksi()
    {
        $transaksi      = DB::table('transaksi')
                        ->where('transaksi.id_users', Auth::user()->id)
                        ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                        ->join('jenis_transaksi','jenis_transaksi.id','=','transaksi.id_jenis_transaksi')
                        ->join('lembaga', 'transaksi.id_lembaga','=','lembaga.id')
                        ->join('paketzakat','detail_transaksi.id_paket_zakat','=','paketzakat.id')
                        ->join('donatur','transaksi.id_donatur','=','donatur.id')
                        ->join('status_transaksi', 'transaksi.id','=','status_transaksi.id_transaksi')
                        ->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
                        ->select('transaksi.id', 'donatur.nama as donatur', 'jenis_transaksi.jenis_transaksi', 'lembaga.nama_lembaga as lembaga', 'status_transaksi.manajer_status as status', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'), DB::raw('group_concat(paketzakat.nama_paket_zakat SEPARATOR ",") as paket'), 'transaksi.tanggal_transfer', 'status_transaksi.komentar', 'status_transaksi.id as status_id', 'status_transaksi.updated_at as update', 'status_transaksi.lazis_status', 'status_transaksi.panzisda_status')
                        ->orderBy('transaksi.id', 'desc')
                        ->groupBy('transaksi.id','donatur.nama','jenis_transaksi.jenis_transaksi','lembaga.nama_lembaga','status_transaksi.manajer_status', 'transaksi.tanggal_transfer', 'status_transaksi.komentar', 'status_transaksi.updated_at', 'status_transaksi.id', 'status_transaksi.lazis_status', 'status_transaksi.panzisda_status')
                        ->get();

        return DataTables::of($transaksi)
        ->addIndexColumn()
        ->addColumn('aksi', function($transaksi) {
            if($transaksi->status == null) {
                $button = '<center><button type="button" name="detail" id="'.$transaksi->id.'" class="detail btn btn-secondary btn-xs">Detail</button>&nbsp;<button type="button" name="edit" id="'.$transaksi->id.'" class="edit btn btn-warning btn-xs">Ubah</button>&nbsp;<a type="button" name="bukti" id="'.$transaksi->id.'" class="bukti btn btn-info btn-xs" target=new>Bukti</a>&nbsp;<button type="button" name="delete" id="'.$transaksi->id.'" class="delete btn btn-danger btn-xs">Hapus</button></center>';
            } else {
                $button = '<center><button type="button" name="detail" id="'.$transaksi->id.'" class="detail btn btn-secondary btn-xs">Detail</button>&nbsp;<a type="button" name="bukti" id="'.$transaksi->id.'" class="bukti btn btn-info btn-xs" target=new>Bukti</a></center>';
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
        ->editColumn('jenis_transaksi', function($transaksi){
            return strtoupper($transaksi->jenis_transaksi);
        })
        ->editColumn('tanggal_transfer', function($transaksi){
            return date('d/m/Y', strtotime($transaksi->tanggal_transfer));
        })
        ->rawColumns(['status','aksi'])
        ->make(true);
    }

    public function detailDonatur($id)
    {
        $data = Donatur::find($id);

        return json_encode($data);
    }

    public function deleteTransaksi($id)
    {

        $data = Transaksi::find($id);
        
        if (empty($data)) {
            return response()->json(['errors' => [0 => 'Data not found !']]);
        }

        if (!$data->delete()) {
            return response()->json(['errors' => [0 => 'Fail to update data']]);
        } else {
            return response()->json(['success' => 'Data is successfully updated']);
        }
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

    public function editTransaksi($id)
    {
        $data['user']               = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();

        $data['transaksi']          = Transaksi::with(['Donatur','JenisTransaksi'])->find($id);
        $data['status']             = StatusTransaksi::where('id_transaksi', $id)->first();
        $detail                     = DetailTransaksi::where('id_transaksi', $id)->get();

        $tmp = [];
        foreach ($detail as $item) {
            $dummy['id_detail'] = $item->id;
            $dummy['id_paket_zakat'] = $item->id_paket_zakat;
            $dummy['jumlah'] = $item->jumlah;
            $tmp[] = $dummy;
        }
        $data['detail'] = $tmp;

        $data['paket']      = PaketZakat::all();
        $data['lembaga']    = Lembaga::all();
        $data['jenis']      = JenisTransaksi::all();
        $donatur            = DB::table('donatur')->leftJoin('transaksi','transaksi.id_donatur','=','donatur.id')->select('donatur.id','donatur.nama')->orderBy('transaksi.id_donatur', 'ASC')->get();
        $data['donatur']    = $donatur->unique('nama', 'alamat');

        return view('admin.dutazakat.edit_transaksi', compact('data'));
    }

    public function updateTransaksi(Request $request)
    {

        //Transaksi
        $transaksi                      = Transaksi::find($request->id);
        $transaksi->keterangan           = $request->keterangan;
        $transaksi->id_lembaga          = $request->id_lembaga;
        $transaksi->id_jenis_transaksi  = $request->jenis_transaksi;
        $transaksi->tanggal_transfer    = date("Y-m-d", strtotime($request->tanggal_transfer));

        //Donatur
        if ($request->id_donatur == "tambah") {
            $count                  = count(Donatur::all());
            $countData              = $count + 1;
            
            $donatur                = new Donatur();
            $donatur->id_donatur    = str_pad($countData, 7, 0, STR_PAD_LEFT).'0';
            $donatur->nama          = $request->nama;
            $donatur->alamat        = $request->alamat;
            $donatur->save();
            $transaksi->id_donatur  = $donatur->id;
        } else {
            $transaksi->id_donatur  = $request->id_donatur;
        }

        //Bukti Transaksi
        if($request->bukti_transaksi != NULL)
        {
            //Bukti Transaksi
            $getTrx                     = Transaksi::whereYear('created_at', date('Y'))->get()->toArray();
            $countTrx                   = count($getTrx) + 1;
            $filename                   = Auth::user()->no_punggung.date('Y').$countTrx.'.'.$request->bukti_transaksi->extension();
            $request->bukti_transaksi->move(public_path().'/bukti/', $filename);
            $transaksi->bukti_transaksi = $filename;
        }

        $nontunai = JenisTransaksi::whereIn('jenis_transaksi', ['transfer','TRANSFER'])->first();
        $nontunai = $nontunai->id ?? null;
        //Jenis Transaksi
        if ($request->jenis_transaksi != $nontunai) {
            $transaksi->rek_bank   = '-';
        } else {
            $transaksi->rek_bank   = $request->rek_bank;
        }

        //Save
        $transaksi->save();

        //Delete Detail Transaksi
        $detail = DetailTransaksi::where('id_transaksi', $transaksi->id)->get();
        foreach($detail as $value) {
            $value->delete();
        }

        //Detail Transaksi
        for ($i=0;$i < count($request->id_paket_zakat);$i++) {
            if($request->id_paket_zakat[$i] != NULL) {
                $detail = new DetailTransaksi();
                $detail->id_transaksi = $transaksi->id;
                $detail->id_paket_zakat = $request->id_paket_zakat[$i];
                $detail->jumlah = str_replace('.', '', $request->jumlah[$i]);
                $detail->save();
            }
        }
        

        //Barang
        $barangx = JenisTransaksi::whereIn('jenis_transaksi', ['barang','BARANG'])->first();
        $barangx = $barangx->id ?? null;
        if(ucwords($request->jenis_transaksi) == $barangx) {
            $barang                     = new Barang();
            $barang->id_transaksi       = $transaksi->id;
            $barang->nama_barang        = strtolower($request->nama_barang);
            $barang->save();
        }

        //Status Transaksi
        $status     = StatusTransaksi::where('id_transaksi', $transaksi->id)->first();
        if ($status->komentar != NULL) {
            $status->id_transaksi = $transaksi->id;
            $status->updated_at = Carbon::today();
            $status->save();
        }

        return back()->with(['success' => 'Data berhasil tersimpan!']);
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

        return view('admin.dutazakat.profil', compact('data'));
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
    
    public function getRekening($id)
    {
        $data = RekeningLembaga::where('id_lembaga', $id)->get();
        return json_encode($data);
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
        return view('admin.dutazakat.laporan', compact('data'));
    }

    public function getDataLaporanRincian()
    {
        $data       = DB::table('transaksi')
                    ->join('lembaga','lembaga.id','=','transaksi.id_lembaga')
                    ->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
                    ->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
                    ->where('status_transaksi.manajer_status', '!=', NULL)
                    ->join('users','users.id','=','transaksi.id_users')
                    ->where('users.id', Auth::user()->id)
                    ->join('role','role.id_users','=','users.id')
                    ->where('role.id_jabatan', 5)
                    ->join('paketzakat','paketzakat.id','=','detail_transaksi.id_paket_zakat')
                    ->join('jenis_transaksi','jenis_transaksi.id','=','transaksi.id_jenis_transaksi')
                    ->join('donatur','donatur.id','=','transaksi.id_donatur')
                    ->select('donatur.nama as donatur','lembaga.nama_lembaga','transaksi.tanggal_transfer', 'jenis_transaksi.jenis_transaksi', 'paketzakat.nama_paket_zakat as paket','detail_transaksi.jumlah')
                    ->orderBy('transaksi.id', 'DESC')
                    ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('tanggal_transfer', function($data){
                return date('d/m/Y', strtotime($data->tanggal_transfer));
            })
            ->editColumn('nama_lembaga', function($data){
                return strtoupper($data->nama_lembaga);
            })
            ->editColumn('jenis_transaksi', function($data){
                return strtoupper($data->jenis_transaksi);
            })
            ->make(true);
    }

}
