<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
use App\Models\LembagaKhusus;
use DataTables;
use Auth;
use DB;
use Session;
use App\Imports\UserImport;
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Mail;
use App\Mail\ResetPasswordNotify;
use Illuminate\Support\Collection;

class PanziswilController extends Controller
{
	private $user_duta;

	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('panziswil');

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

		$this->user             = DB::table('users')
								->join('role','role.id_users','=','users.id')
								->leftJoin('wilayah','wilayah.id','=','users.id_wilayah')
								->select('users.*','role.id_jabatan', 'wilayah.nama_wilayah')
								->where('role.id_jabatan', '!=', 5)
								->get();
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function index()
	{

		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();

		$user               = Wilayah::all();

		$tmp1 = [];
		foreach ($user as $item) {
			$dummy['name'] = $item->nama_wilayah;

			$target         = DB::table('users')
							->where('users.id_wilayah', $item->id)
							->where('users.deleted_at', NULL)
							->join('role','role.id_users','=','users.id')
							->where('role.id_jabatan', 5)
							->join('group','group.id','=','role.id_group')
							->select(DB::raw('CAST(SUM(group.target) as UNSIGNED) as target'))
							->first();

			$y              = DB::table('users')
							->where('users.id_wilayah', $item->id)
							->where('users.deleted_at', NULL)
							->join('role','role.id_users','=','users.id')
							->where('role.id_jabatan', 5)
							->join('group','group.id','=','role.id_group')
							->join('transaksi','transaksi.id_users','=','users.id')
							->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
							->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
							->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as y'))
							->where('status_transaksi.lazis_status', '!=', NULL)
							->first();

			$dummy['y'] = ($y->y != 0) ? $y->y : 0;
			$dummy['target'] = $target->target;
			$dummy['persentase'] = ($y->y != 0) ? number_format(($y->y / $target->target) * 100, 2) : 0;
			$tmp1[] = $dummy;
		}
		$data['panzisda'] = $tmp1;

		$data['target'] = 0;
		foreach ($data['panzisda'] as $key => $value) {
			if ($value['target'] > $data['target']) {
				$data['target'] = (int) str_replace('.', '', $value['target']);
			}
		}

		//Lembaga
		$lembaga  = DB::table('transaksi')
						->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
						->leftJoin('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
						->join('lembaga', 'transaksi.id_lembaga','=','lembaga.id')
						->select('lembaga.nama_lembaga as name', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as y'))
						->where('status_transaksi.lazis_status', '!=', NULL)
						->groupBy('name')
						->get()->toArray();

		$countY = 0;
		foreach ($lembaga as $item) {
			$countY = $countY + $item->y;
		}

		$tmp2 = [];
		foreach ($lembaga as $item) {
			$dummys['name'] = strtoupper($item->name);
			$dummys['y'] = $item->y;
			$dummys['persentase'] = number_format(($item->y / $countY) * 100, 2);
			$tmp2[] = $dummys;
		}
		$data['lembaga'] = $tmp2;

		//paket zakat
		$paketzakat  = DB::table('transaksi')
						->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
						->leftJoin('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
						->join('paketzakat', 'detail_transaksi.id_paket_zakat','=','paketzakat.id')
						->select('paketzakat.nama_paket_zakat as name', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as y'))
						->where('status_transaksi.lazis_status', '!=', NULL)
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

		return view('admin.panziswil.beranda', compact('data'));

	}

	//EXPORT & IMPORT
	public function import_excel(Request $request) 
	{
		// validasi
		// $this->validate($request, [
		// 	'file' => 'required|mimes:csv,xls,xlsx'
		// ]);

		// menangkap file excel
		$file = $request->file('file');

		// membuat nama file unik
		$nama_file = rand().$file->getClientOriginalName();

		// upload ke folder file_siswa di dalam folder public
		$file->move('import_excel',$nama_file);

		// import data
		Excel::import(new UserImport, public_path('/import_excel/'.$nama_file));
		// notifikasi dengan session
		Session::flash('sukses','Data Siswa Berhasil Diimport!');

		// alihkan halaman kembali
		return redirect()->route('panziswil.user');
	}

	public function export_excel()
	{
		return Excel::download(new UserExport, 'user.xlsx');
	}
	//END EXPORT & IMPORT

	//TAMPILAN VIEW
	public function getUser()
	{
		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();      
		$data['wilayah']            = Wilayah::all();
		$data['jabatan']            = Jabatan::all();
		$data['group']              = Group::all();
		$data['lembaga']            = Lembaga::all();
		$data['panzisda']           = DB::table('users')->join('role','role.id_users','=','users.id')->select('users.*')->where('role.id_jabatan', 2)->get()->toArray();
		$data['manajerarea']        = DB::table('users')->join('role','role.id_users','=','users.id')->select('users.*')->where('role.id_jabatan', 3)->get()->toArray();
		$data['manajer']            = DB::table('users')->join('role','role.id_users','=','users.id')->select('users.*')->where('role.id_jabatan', 4)->get()->toArray();
		return view('admin.panziswil.user', compact('data'));
	}

	public function getWilayah()
	{
		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();
		return view('admin.panziswil.wilayah', compact('data'));
	}
	
	public function getLembaga()
	{
		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();
		$data['wilayah']            = Wilayah::all();
		return view('admin.panziswil.lembaga', compact('data'));
	}

	public function getJenisTransaksi()
	{
		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
		return view('admin.panziswil.jenis_transaksi', compact('data'));
	}

	public function getPaket()
	{
		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
		return view('admin.panziswil.paket', compact('data'));
	}

	public function getTransaksi()
	{
		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
		return view('admin.panziswil.transaksi', compact('data'));
	}

	public function getRekeningLembaga()
	{
		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
		$data['lembaga']            = Lembaga::all();
		return view('admin.panziswil.rekening_lembaga', compact('data'));
	}

	public function getDistribusi()
	{
		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
		$data['paket']      = DB::table('paketzakat')->select('paketzakat.*')->whereNotIn('paketzakat.id', function($query) {
			$query->select('id_paket_zakat')->from('distribusi')->get();
		})->get();
		return view('admin.panziswil.distribusi', compact('data'));
	}
	//END VIEW

	//JENIS TRANSAKSI
	public function simpanJenisTransaksi(Request $request)
	{
		$data = new JenisTransaksi();
		$data->jenis_transaksi = strtolower($request->jenis_transaksi);
		$data->save();
	}

	public function getDataJenisTransaksi()
	{
		$data = JenisTransaksi::all();

		return DataTables::of($data)
		->addIndexColumn()
		->addColumn('aksi', function($data) {
			$button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-secondary btn-xs">Edit</button>&nbsp;<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-xs">Hapus</button>';
			return $button;
		})
		->editColumn('jenis_transaksi', function($data){
			return ucwords($data->jenis_transaksi);
		})
		->rawColumns(['aksi'])
		->make(true);
	}

	public function editJenisTransaksi($id)
	{
		$data = JenisTransaksi::find($id);
		
		return json_encode($data);
	}

	public function updateJenisTransaksi(Request $request)
	{
		// dd($request->all());
		$data = JenisTransaksi::find($request->id);
		if (empty($data)) {
			return response()->json(['errors' => [0 => 'Data not found !']]);
		}
		$data->jenis_transaksi = strtolower($request->edit_jenis_transaksi);
		$data->save();

		return response()->json(['success' => 'update success stored!']);
	}

	public function deleteJenisTransaksi($id)
	{

		$data = JenisTransaksi::find($id);
		
		if (empty($data)) {
			return response()->json(['errors' => [0 => 'Data not found !']]);
		}

		if (!$data->delete()) {
			return response()->json(['errors' => [0 => 'Fail to update data']]);
		} else {
			return response()->json(['success' => 'Data is successfully updated']);
		}
	}
	//END JENIS TRANSAKSI

	//REKENING LEMBAGA
	public function simpanRekeningLembaga(Request $request)
	{
		$data = new RekeningLembaga();
		$data->id_lembaga = $request->id_lembaga;
		$data->norek = $request->norek;
		$data->save();
	}

	public function getDataRekeningLembaga()
	{
		$data = DB::table('rekening_lembaga')->join('lembaga','lembaga.id','=','rekening_lembaga.id_lembaga')->select('lembaga.nama_lembaga','rekening_lembaga.*')->orderBy('lembaga.id', 'ASC')->get();

		return DataTables::of($data)
		->addIndexColumn()
		->addColumn('aksi', function($data) {
			$button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-secondary btn-xs">Edit</button>&nbsp;<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-xs">Hapus</button>';
			return $button;
		})
		->editColumn('nama_lembaga', function($data){
			return strtoupper(strtolower($data->nama_lembaga));
		})
		->rawColumns(['aksi'])
		->make(true);
	}

	public function editRekeningLembaga($id)
	{
		$data = DB::table('rekening_lembaga')->join('lembaga','lembaga.id','=','rekening_lembaga.id_lembaga')->select('lembaga.nama_lembaga','rekening_lembaga.*')->where('rekening_lembaga.id', $id)->first();
		
		return json_encode($data);
	}

	public function updateRekeningLembaga(Request $request)
	{
		// dd($request->all());
		$data = RekeningLembaga::find($request->id);
		if (empty($data)) {
			return response()->json(['errors' => [0 => 'Data not found !']]);
		}
		$data->id_lembaga = $request->edit_lembaga;
		$data->norek = $request->edit_norek;
		$data->save();

		return response()->json(['success' => 'update success stored!']);
	}

	public function deleteRekeningLembaga($id)
	{

		$data = RekeningLembaga::find($id);
		
		if (empty($data)) {
			return response()->json(['errors' => [0 => 'Data not found !']]);
		}

		if (!$data->delete()) {
			return response()->json(['errors' => [0 => 'Fail to update data']]);
		} else {
			return response()->json(['success' => 'Data is successfully updated']);
		}
	}
	//END REKENING LEMBAGA

	//DISTRIBUSI
	public function simpanDistribusi(Request $request)
	{
		$data = new Distribusi();
		$data->id_paket_zakat   = $request->id_paket_zakat;
		$data->panzisnas        = $request->panzisnas;
		$data->panziswil        = $request->panziswil;
		$data->panzisda         = $request->panzisda;
		$data->cabang           = $request->cabang;
		$data->mitra_strategis  = $request->mitra_strategis;
		$data->duta             = $request->duta;
		$data->save();
	}

	public function getDataDistribusi()
	{
		$data = DB::table('distribusi')->join('paketzakat','paketzakat.id','=','distribusi.id_paket_zakat')->select('paketzakat.nama_paket_zakat','distribusi.*')->orderBy('distribusi.id_paket_zakat', 'ASC')->get();

		return DataTables::of($data)
		->addIndexColumn()
		->addColumn('aksi', function($data) {
			$button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-secondary btn-xs">Edit</button>&nbsp;<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-xs">Hapus</button>';
			return $button;
		})
		->rawColumns(['aksi'])
		->make(true);
	}

	public function editDistribusi($id)
	{
		$data = DB::table('distribusi')->join('paketzakat','paketzakat.id','=','distribusi.id_paket_zakat')->select('paketzakat.nama_paket_zakat','distribusi.*')->where('distribusi.id', $id)->first();
		
		return json_encode($data);
	}

	public function updateDistribusi(Request $request)
	{
		$data = Distribusi::find($request->id);
		if (empty($data)) {
			return response()->json(['errors' => [0 => 'Data not found !']]);
		}
		$data->id_paket_zakat   = $request->edit_paket_zakat;
		$data->panzisnas        = $request->edit_panzisnas;
		$data->panziswil        = $request->edit_panziswil;
		$data->panzisda         = $request->edit_panzisda;
		$data->cabang           = $request->edit_cabang;
		$data->mitra_strategis  = $request->edit_mitra_strategis;
		$data->duta             = $request->edit_duta;
		$data->save();

		return response()->json(['success' => 'update success stored!']);
	}

	public function deleteDistribusi($id)
	{

		$data = Distribusi::find($id);
		
		if (empty($data)) {
			return response()->json(['errors' => [0 => 'Data not found !']]);
		}

		if (!$data->delete()) {
			return response()->json(['errors' => [0 => 'Fail to update data']]);
		} else {
			return response()->json(['success' => 'Data is successfully updated']);
		}
	}
	//END DISTRIBUSI

	//USER
	public function tambahUser()
	{
		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();

		$data['jabatan']        = Jabatan::whereIn('nama_jabatan', ['LAZIS','PANZISDA'])->get();
		$data['wilayah']        = Wilayah::all();
		$data['group']          = Group::all();
		$data['lembaga']        = Lembaga::all();

		$data['panzisda']       = DB::table('users')->join('role','role.id_users','=','users.id')->select('users.*')->where('role.id_jabatan', 2)->get()->toArray();

		$data['manajerarea']    = DB::table('users')->join('role','role.id_users','=','users.id')->select('users.*')->where('role.id_jabatan', 3)->get()->toArray();

		$data['manajer']        = DB::table('users')->join('role','role.id_users','=','users.id')->select('users.*')->where('role.id_jabatan', 4)->get()->toArray();

		return view('admin.panziswil.tambahuser', compact('data'));
	}

	public function simpanUser(Request $request)
	{
		//Checked
		$nubrow = count(User::all())+1;

		//Simpan
		$user = new User();
		$no_punggung = str_pad($request->id_wilayah, 2, '0', STR_PAD_LEFT).str_pad($nubrow, 4, 0, STR_PAD_LEFT);;
		$user->no_punggung = $no_punggung;
		$user->nama = $request->nama;
		$user->alamat = $request->alamat;
		$user->npwp = $request->npwp;
		$user->no_hp = $request->no_hp;
		$user->email = $request->email;
		$user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password
		$user->id_wilayah = $request->id_wilayah;

		//Surat Tugas
		// $filename                   = $no_punggung.'_'.$request->nama.'.'.$request->surat_tugas->extension();
		// $request->surat_tugas->move(public_path().'/surat_tugas/', $filename);
		// $user->surat_tugas = $filename;

		$user->save();

		foreach ($request->id_jabatan as $id_jabatan) {
			if ($id_jabatan == "5") {
				$role = new Role();
				$role->id_users = $user->id;
				$role->id_jabatan = $id_jabatan;
				$role->id_atasan = $request->manajer_id;
				$role->id_group = $request->group_id;
				$role->save();
			} else if ($id_jabatan == "4") {
				$role = new Role();
				$role->id_users = $user->id;
				$role->id_jabatan = $id_jabatan;
				$role->id_atasan = $request->manajerarea_id;
				$role->save();
			} else if ($id_jabatan == "3") {
				$role = new Role();
				$role->id_users = $user->id;
				$role->id_jabatan = $id_jabatan;
				$role->id_atasan = $request->panzisda_id;
				$role->save();
			} else if ($id_jabatan == "2") {
				$role = new Role();
				$role->id_users = $user->id;
				$role->id_jabatan = $id_jabatan;
				$role->id_atasan = Auth::user()->id;
				$role->save();
			} else if ($id_jabatan == "1") {
				$role = new Role();
				$role->id_users = $user->id;
				$role->id_jabatan = $id_jabatan;
				$role->save();
			} else {
				$role = new Role();
				$role->id_users = $user->id;
				$role->id_jabatan = $id_jabatan;
				$role->id_lembaga = $request->id_lembaga;
				$role->save();
			}
			
		}
	}

	public function getDataUser()
	{
		
		$user       = DB::table('users')
					->where('users.deleted_at', NULL)
					->join('wilayah','wilayah.id','=','users.id_wilayah')
					->leftJoin('role','role.id_users','=','users.id')
					->leftJoin('jabatan','jabatan.id','=','role.id_jabatan')
					->select('users.id', 'users.nama', 'users.no_punggung', DB::raw('group_concat(jabatan.nama_jabatan SEPARATOR ",") as jabatan'), 'wilayah.nama_wilayah as wilayah', DB::raw('group_concat(IF(role.id_atasan IS NULL, "null", role.id_atasan)) as id_atasan'), DB::raw('group_concat(IF(role.id_group IS NULL, "null", role.id_group)) as id_group'), DB::raw('group_concat(IF(role.id_lembaga IS NULL, "null", role.id_lembaga)) as id_lembaga'))
					->where('users.no_punggung', '!=', '000001')
					->orderBy('users.id', 'ASC')
					// ->orderBy(DB::raw('role.id_jabatan IS NULL'), 'DESC')
					// ->orderBy(DB::raw('role.id_atasan IS NULL'), 'DESC')
					->groupBy('users.id','users.no_punggung','users.nama','wilayah.nama_wilayah')
					->get();

		return DataTables::of($user)
			->addIndexColumn()
			->addColumn('aksi', function($user) {

				$jabatan = explode(",", $user->jabatan);
				$atasan = explode(",", $user->id_atasan);
				$group = explode(",", $user->id_group);
				$lembaga = explode(",", $user->id_lembaga);
				$status = 0;

				for ($i=0;$i<count($jabatan);$i++) {
					if ($user->jabatan == null OR $jabatan[$i] == "null" OR ($jabatan[$i] == "DUTA ZAKAT" AND $atasan[$i] == "null") OR ($jabatan[$i] == "DUTA ZAKAT" AND $group[$i] == "null") OR ($jabatan[$i] == "LAZIS" AND $lembaga[$i] == "null") OR ($jabatan[$i] == "MANAJER AREA" AND $atasan[$i] == "null") OR ($jabatan[$i] == "MANAJER GROUP" AND $atasan[$i] == "null")) {
						$status = 1;
						break;
					}
				}
				if ($status == 1) {
					$button = '<center><button type="button" name="edit" id="'.$user->id.'" class="edit btn btn-warning btn-xs">Verifikasi</button>&nbsp;<button type="button" name="delete" id="'.$user->id.'" class="delete btn btn-danger btn-xs">Hapus</button></center>';
				} else {
					$button = '<center><button type="button" name="edit" id="'.$user->id.'" class="edit btn btn-secondary btn-xs">Edit</button>&nbsp;<button type="button" name="delete" id="'.$user->id.'" class="delete btn btn-danger btn-xs">Hapus</button></center>';
				}
				return $button;
			})
			->rawColumns(['aksi'])
			->make(true);
		
	}
	
	public function editUser($id)
	{
		$data['user']       = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']  = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_lazis']      = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
		$data['user_panzisda']         = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['editUser']       = User::find($id);
		$data['role']           = Role::where('id_users', $id)->get();

		$tmp = [];
		foreach ($data['role'] as $item) {
			$tmp[] = $item->id_jabatan;
		}
		$data['tmp'] = $tmp;

		$data['group']          = Group::all();
		$data['panzisda']       = DB::table('users')
								->join('role','role.id_users','=','users.id')
								->select('users.*')
								->where('role.id_jabatan', 2)
								->where('users.id_wilayah', $data['editUser']->id_wilayah)
								->get()->toArray();
		$data['manajerarea']    = DB::table('users')
								->join('role','role.id_users','=','users.id')
								->select('users.*')
								->where('role.id_jabatan', 3)
								->where('users.id_wilayah', $data['editUser']->id_wilayah)
								->get()->toArray();
		$data['manajer']        = DB::table('users')
								->join('role','role.id_users','=','users.id')
								->select('users.*')
								->where('role.id_jabatan', 4)
								->where('users.id_wilayah', $data['editUser']->id_wilayah)
								->get()->toArray();
		$data['jabatan']        = Jabatan::all();
		$data['lembaga']        = Lembaga::all();

		return view('admin.panziswil.edituser', compact('data'));
	}

	public function updateUser(Request $request)
	{
		$user = User::find($request->id);
		if (empty($user)) {
			return response()->json(['errors' => [0 => 'Data not found !']]);
		}
		$user->nama = $request->nama;
		$user->alamat = $request->alamat;
		$user->npwp = $request->npwp;
		$user->no_hp = $request->no_hp;
		$user->email = $request->email;

		// if ($user->surat_tugas == null or $request->surat_tugas != null) {
		//     //Surat Tugas
		//     $filename                   = $user->no_punggung.'_'.$request->nama.'.'.$request->surat_tugas->extension();
		//     $request->surat_tugas->move(public_path().'/surat_tugas/', $filename);
		//     $user->surat_tugas = $filename;
		// }
		
		$user->save();

		$role_user = Role::where('id_users', $request->id)->get();
		foreach ($role_user as $item) {
			$item->delete();
		}

		foreach ($request->jabatan_id as $id_jabatan) {

			$role = new Role();

			if ($id_jabatan == 6) {
				$role->id_users = $request->id;
				$role->id_jabatan = $id_jabatan;
				$role->id_lembaga = $request->edit_lembaga;
				$role->save();
			} else if ($id_jabatan == 5) {
				$role->id_users = $request->id;
				$role->id_jabatan = $id_jabatan;
				$role->id_atasan = $request->manajer_id;
				$role->id_group = $request->group_id;
				$role->save();
			} else if ($id_jabatan == 4) {
				$role->id_users = $request->id;
				$role->id_jabatan = $id_jabatan;
				$role->id_atasan = $request->spv_id;
				$role->save();
			} else if ($id_jabatan == 3) {
				$role->id_users = $request->id;
				$role->id_jabatan = $id_jabatan;
				$role->id_atasan = $request->panzisda_id;
				$role->save();
			} else if ($id_jabatan == 2) {
				$role->id_users = $request->id;
				$role->id_jabatan = $id_jabatan;
				$role->id_atasan = Auth::user()->id;
				$role->save();
			} else {
				$role->id_users = $request->id;
				$role->id_jabatan = $id_jabatan;
				$role->save();
			}
		};

		return response()->json(['success' => 'update success stored!']);
	}
	
	public function resetPassword(Request $request)
	{
		$user = User::find($request->id);
		$user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
		
		if(!$user->save()) {
			return redirect('/panziswil/user')->with(['errors' => 'Gagal reset password!']);
		} else {
			//Mail::to($user->email)->send(new ResetPasswordNotify($user));
			return redirect('/panziswil/user')->with(['success' => 'Password pengguna "'.$data->no_punggung.'" berhasil di reset!']);
		}
	}

	public function deleteUser($id)
	{
		// Check if super user or not
		if($id == 1){
			return response()->json(['errors' => [0 => 'Cannot delete panziswil!']]);
		}

		$user = User::find($id);
		
		if (empty($user)) {
			return response()->json(['errors' => [0 => 'Data not found !']]);
		}

		if (!$user->delete()) {
			return response()->json(['errors' => [0 => 'Fail to update data']]);
		} else {
			return response()->json(['success' => 'Data is successfully updated']);
		}
	}
	//END USER

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */

	//WILAYAH
	public function editWilayah($id)
	{
		$data = Wilayah::find($id);
		
		return json_encode($data);
	}

	public function updateWilayah(Request $request)
	{
		// dd($request->all());
		$data = Wilayah::find($request->id);
		if (empty($data)) {
			return response()->json(['errors' => [0 => 'Data not found !']]);
		}
		$data->nama_wilayah = $request->edit_wilayah;
		$data->save();

		return response()->json(['success' => 'update success stored!']);
	}

	public function deleteWilayah($id)
	{

		$data = Wilayah::find($id);
		
		if (empty($data)) {
			return response()->json(['errors' => [0 => 'Data not found !']]);
		}

		if (!$data->delete()) {
			return response()->json(['errors' => [0 => 'Fail to update data']]);
		} else {
			return response()->json(['success' => 'Data is successfully updated']);
		}
	}

	public function simpanWilayah(Request $request)
	{
		$wilayah = new Wilayah();
		$wilayah->nama_wilayah = $request->nama_wilayah;
		$wilayah->save();

		return response()->json(['success' => 'success stored!']);
	}

	public function getDataWilayah()
	{
		$wilayah = Wilayah::all();

		return DataTables::of($wilayah)
		->addIndexColumn()
		->addColumn('aksi', function($wilayah) {
			$button = '<button type="button" name="edit" id="'.$wilayah->id.'" class="edit btn btn-secondary btn-xs">Edit</button>&nbsp;<button type="button" name="delete" id="'.$wilayah->id.'" class="delete btn btn-danger btn-xs">Hapus</button>';
			return $button;
		})
		->rawColumns(['aksi'])
		->make(true);
	}
	//END WILAYAH
	
	public function simpanLembaga(Request $request)
	{
		$lembaga = new Lembaga();
		$lembaga->nama_lembaga  = strtolower($request->nama_lembaga);
		$lembaga->jenis         = $request->jenis;
		$lembaga->status        = $request->status;
		$lembaga->save();

		if ($request->jenis == 'cabang') {
			foreach ($request->id_wilayah as $item) {
				$khusus = new LembagaKhusus();
				$khusus->id_lembaga = $lembaga->id;
				$khusus->id_wilayah = $item;
				$khusus->save();
			}
		}

		return response()->json(['success' => 'success stored!']);
	}

	public function simpanPaket(Request $request)
	{
		$paket = new PaketZakat();
		$paket->nama_paket_zakat = $request->nama_paket_zakat;
		$paket->save();

		return response()->json(['success' => 'success stored!']);
	}
	
	public function getDataLembaga()
	{
		$lembaga = DB::table('lembaga')->leftJoin('lembaga_khusus','lembaga.id','=','lembaga_khusus.id_lembaga')->leftJoin('wilayah','wilayah.id','=','lembaga_khusus.id_wilayah')->select('lembaga.id','lembaga.nama_lembaga','lembaga.jenis','lembaga.status', DB::raw('group_concat(wilayah.nama_wilayah SEPARATOR ", ") as wilayah'))->groupBy('lembaga.id','lembaga.nama_lembaga','lembaga.jenis','lembaga.status')->get();

		return DataTables::of($lembaga)
		->addIndexColumn()
		->addColumn('aksi', function($lembaga) {
			$button = '<button type="button" name="edit" id="'.$lembaga->id.'" class="edit btn btn-secondary btn-xs">Edit</button>&nbsp;<button type="button" name="delete" id="'.$lembaga->id.'" class="delete btn btn-danger btn-xs">Hapus</button>';
			return $button;
		})
		->editColumn('nama_lembaga', function($lembaga){
			return strtoupper($lembaga->nama_lembaga);
		})
		->editColumn('jenis', function($lembaga){
			return ucwords($lembaga->jenis);
		})
		->editColumn('status', function($lembaga){
			return ucwords($lembaga->status);
		})
		->editColumn('wilayah', function($lembaga){
			return ucwords($lembaga->wilayah);
		})
		->rawColumns(['aksi'])
		->make(true);
	}

	public function getDataPaket()
	{
		$paket = PaketZakat::all();

		return DataTables::of($paket)
		->addIndexColumn()
		->addColumn('aksi', function($paket) {
			$button = '<button type="button" name="edit" id="'.$paket->id.'" class="edit btn btn-secondary btn-xs">Edit</button>&nbsp;<button type="button" name="delete" id="'.$paket->id.'" class="delete btn btn-danger btn-xs">Hapus</button>';
			return $button;
		})
		->rawColumns(['aksi'])
		->make(true);
	}
	
	public function deleteLembaga($id)
	{

		$data = Lembaga::find($id);
		
		if (empty($data)) {
			return response()->json(['errors' => [0 => 'Data not found !']]);
		}

		if (!$data->delete()) {
			return response()->json(['errors' => [0 => 'Fail to update data']]);
		} else {
			return response()->json(['success' => 'Data is successfully updated']);
		}
	}

	public function deletePaket($id)
	{

		$data = PaketZakat::find($id);
		
		if (empty($data)) {
			return response()->json(['errors' => [0 => 'Data not found !']]);
		}

		if (!$data->delete()) {
			return response()->json(['errors' => [0 => 'Fail to update data']]);
		} else {
			return response()->json(['success' => 'Data is successfully updated']);
		}
	}

	public function getDataTransaksi($id)
	{
		if ($id == 0) {
			$transaksi      = DB::table('transaksi')
							->join('lembaga', 'transaksi.id_lembaga','=','lembaga.id')
							->join('users', 'transaksi.id_users','=','users.id')
							->where('users.deleted_at', NULL)
							->join('wilayah','wilayah.id','=','users.id_wilayah')
							->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
							->join('jenis_transaksi','jenis_transaksi.id','=','transaksi.id_jenis_transaksi')
							->join('paketzakat','detail_transaksi.id_paket_zakat','=','paketzakat.id')
							->join('donatur','transaksi.id_donatur','=','donatur.id')
							->join('status_transaksi', 'transaksi.id','=','status_transaksi.id_transaksi')
							->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
							->select('transaksi.id', 'wilayah.nama_wilayah as wilayah', 'donatur.nama as donatur', 'users.nama as user', 'jenis_transaksi.jenis_transaksi', 'lembaga.nama_lembaga as lembaga', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'), DB::raw('group_concat(paketzakat.nama_paket_zakat SEPARATOR ", ") as paket'), 'status_transaksi.panzisda_status', 'status_transaksi.komentar', 'status_transaksi.updated_at as update', 'status_transaksi.lazis_status', 'status_transaksi.manajer_status')
							->groupBy('transaksi.id','wilayah.nama_wilayah','donatur.nama','users.nama','jenis_transaksi.jenis_transaksi','lembaga.nama_lembaga', 'status_transaksi.panzisda_status', 'status_transaksi.komentar', 'status_transaksi.updated_at', 'status_transaksi.lazis_status', 'status_transaksi.manajer_status')
							->orderBy('transaksi.id', 'DESC')
							->get();
		} else if ($id == 1) {
			$transaksi      = DB::table('transaksi')
							->join('lembaga', 'transaksi.id_lembaga','=','lembaga.id')
							->join('users', 'transaksi.id_users','=','users.id')
							->where('users.deleted_at', NULL)
							->join('wilayah','wilayah.id','=','users.id_wilayah')
							->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
							->join('jenis_transaksi','jenis_transaksi.id','=','transaksi.id_jenis_transaksi')
							->join('paketzakat','detail_transaksi.id_paket_zakat','=','paketzakat.id')
							->join('donatur','transaksi.id_donatur','=','donatur.id')
							->join('status_transaksi', 'transaksi.id','=','status_transaksi.id_transaksi')
							->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
							->select('transaksi.id', 'wilayah.nama_wilayah as wilayah', 'donatur.nama as donatur', 'users.nama as user', 'jenis_transaksi.jenis_transaksi', 'lembaga.nama_lembaga as lembaga', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'), DB::raw('group_concat(paketzakat.nama_paket_zakat SEPARATOR ", ") as paket'), 'status_transaksi.panzisda_status', 'status_transaksi.komentar', 'status_transaksi.updated_at as update', 'status_transaksi.lazis_status', 'status_transaksi.manajer_status')
							->where('status_transaksi.lazis_status', '!=', NULL)
							->groupBy('transaksi.id','wilayah.nama_wilayah','donatur.nama','users.nama','jenis_transaksi.jenis_transaksi','lembaga.nama_lembaga', 'status_transaksi.panzisda_status', 'status_transaksi.komentar', 'status_transaksi.updated_at', 'status_transaksi.lazis_status', 'status_transaksi.manajer_status')
							->orderBy('transaksi.id', 'DESC')
							->get();
		} else if ($id == 2) {
			$transaksi      = DB::table('transaksi')
							->join('lembaga', 'transaksi.id_lembaga','=','lembaga.id')
							->join('users', 'transaksi.id_users','=','users.id')
							->where('users.deleted_at', NULL)
							->join('wilayah','wilayah.id','=','users.id_wilayah')
							->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
							->join('jenis_transaksi','jenis_transaksi.id','=','transaksi.id_jenis_transaksi')
							->join('paketzakat','detail_transaksi.id_paket_zakat','=','paketzakat.id')
							->join('donatur','transaksi.id_donatur','=','donatur.id')
							->join('status_transaksi', 'transaksi.id','=','status_transaksi.id_transaksi')
							->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
							->select('transaksi.id', 'wilayah.nama_wilayah as wilayah', 'donatur.nama as donatur', 'users.nama as user', 'jenis_transaksi.jenis_transaksi', 'lembaga.nama_lembaga as lembaga', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'), DB::raw('group_concat(paketzakat.nama_paket_zakat SEPARATOR ", ") as paket'), 'status_transaksi.panzisda_status', 'status_transaksi.komentar', 'status_transaksi.updated_at as update', 'status_transaksi.lazis_status', 'status_transaksi.manajer_status')
							->where('status_transaksi.komentar', '!=', NULL)
							->where('status_transaksi.updated_at', NULL)
							->groupBy('transaksi.id','wilayah.nama_wilayah','donatur.nama','users.nama','jenis_transaksi.jenis_transaksi','lembaga.nama_lembaga', 'status_transaksi.panzisda_status', 'status_transaksi.komentar', 'status_transaksi.updated_at', 'status_transaksi.lazis_status', 'status_transaksi.manajer_status')
							->orderBy('transaksi.id', 'DESC')
							->get();
		} else if ($id == 3) {
			$transaksi      = DB::table('transaksi')
							->join('lembaga', 'transaksi.id_lembaga','=','lembaga.id')
							->join('users', 'transaksi.id_users','=','users.id')
							->where('users.deleted_at', NULL)
							->join('wilayah','wilayah.id','=','users.id_wilayah')
							->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
							->join('jenis_transaksi','jenis_transaksi.id','=','transaksi.id_jenis_transaksi')
							->join('paketzakat','detail_transaksi.id_paket_zakat','=','paketzakat.id')
							->join('donatur','transaksi.id_donatur','=','donatur.id')
							->join('status_transaksi', 'transaksi.id','=','status_transaksi.id_transaksi')
							->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
							->select('transaksi.id', 'wilayah.nama_wilayah as wilayah', 'donatur.nama as donatur', 'users.nama as user', 'jenis_transaksi.jenis_transaksi', 'lembaga.nama_lembaga as lembaga', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'), DB::raw('group_concat(paketzakat.nama_paket_zakat SEPARATOR ", ") as paket'), 'status_transaksi.panzisda_status', 'status_transaksi.komentar', 'status_transaksi.updated_at as update', 'status_transaksi.lazis_status', 'status_transaksi.manajer_status')
							->where('status_transaksi.manajer_status', '!=', NULL)
							->where('status_transaksi.panzisda_status', '!=', NULL)
							->where('status_transaksi.lazis_status', NULL)
							->groupBy('transaksi.id','wilayah.nama_wilayah','donatur.nama','users.nama','jenis_transaksi.jenis_transaksi','lembaga.nama_lembaga', 'status_transaksi.panzisda_status', 'status_transaksi.komentar', 'status_transaksi.updated_at', 'status_transaksi.lazis_status', 'status_transaksi.manajer_status')
							->orderBy('transaksi.id', 'DESC')
							->get();
		} else if ($id == 4) {
			$transaksi      = DB::table('transaksi')
							->join('lembaga', 'transaksi.id_lembaga','=','lembaga.id')
							->join('users', 'transaksi.id_users','=','users.id')
							->where('users.deleted_at', NULL)
							->join('wilayah','wilayah.id','=','users.id_wilayah')
							->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
							->join('jenis_transaksi','jenis_transaksi.id','=','transaksi.id_jenis_transaksi')
							->join('paketzakat','detail_transaksi.id_paket_zakat','=','paketzakat.id')
							->join('donatur','transaksi.id_donatur','=','donatur.id')
							->join('status_transaksi', 'transaksi.id','=','status_transaksi.id_transaksi')
							->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
							->select('transaksi.id', 'wilayah.nama_wilayah as wilayah', 'donatur.nama as donatur', 'users.nama as user', 'jenis_transaksi.jenis_transaksi', 'lembaga.nama_lembaga as lembaga', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'), DB::raw('group_concat(paketzakat.nama_paket_zakat SEPARATOR ", ") as paket'), 'status_transaksi.panzisda_status', 'status_transaksi.komentar', 'status_transaksi.updated_at as update', 'status_transaksi.lazis_status', 'status_transaksi.manajer_status')
							->where('status_transaksi.manajer_status', '!=', NULL)
							->where('status_transaksi.panzisda_status', NULL)
							->groupBy('transaksi.id','wilayah.nama_wilayah','donatur.nama','users.nama','jenis_transaksi.jenis_transaksi','lembaga.nama_lembaga', 'status_transaksi.panzisda_status', 'status_transaksi.komentar', 'status_transaksi.updated_at', 'status_transaksi.lazis_status', 'status_transaksi.manajer_status')
							->orderBy('transaksi.id', 'DESC')
							->get();
		} else {
			$transaksi      = DB::table('transaksi')
							->join('lembaga', 'transaksi.id_lembaga','=','lembaga.id')
							->join('users', 'transaksi.id_users','=','users.id')
							->where('users.deleted_at', NULL)
							->join('wilayah','wilayah.id','=','users.id_wilayah')
							->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
							->join('jenis_transaksi','jenis_transaksi.id','=','transaksi.id_jenis_transaksi')
							->join('paketzakat','detail_transaksi.id_paket_zakat','=','paketzakat.id')
							->join('donatur','transaksi.id_donatur','=','donatur.id')
							->join('status_transaksi', 'transaksi.id','=','status_transaksi.id_transaksi')
							->leftJoin('barang','barang.id_transaksi','=','transaksi.id')
							->select('transaksi.id', 'wilayah.nama_wilayah as wilayah', 'donatur.nama as donatur', 'users.nama as user', 'jenis_transaksi.jenis_transaksi', 'lembaga.nama_lembaga as lembaga', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'), DB::raw('group_concat(paketzakat.nama_paket_zakat SEPARATOR ", ") as paket'), 'status_transaksi.panzisda_status', 'status_transaksi.komentar', 'status_transaksi.updated_at as update', 'status_transaksi.lazis_status', 'status_transaksi.manajer_status')
							->where('status_transaksi.manajer_status', NULL)
							->where('status_transaksi.komentar', NULL)
							->orWhere('status_transaksi.updated_at', '!=', NULL)
							->groupBy('transaksi.id','wilayah.nama_wilayah','donatur.nama','users.nama','jenis_transaksi.jenis_transaksi','lembaga.nama_lembaga', 'status_transaksi.panzisda_status', 'status_transaksi.komentar', 'status_transaksi.updated_at', 'status_transaksi.lazis_status', 'status_transaksi.manajer_status')
							->orderBy('transaksi.id', 'DESC')
							->get();
		}

		$transaksi      = $transaksi->sortByDesc('id');

		return DataTables::of($transaksi)
		->addIndexColumn()
		->addColumn('aksi', function($transaksi) {
			if ($transaksi->lazis_status != null or $transaksi->komentar != NULL) {
				$button = '<center><button type="button" name="detail" id="'.$transaksi->id.'" class="detail btn btn-secondary btn-xs">DETAIL</button>&nbsp;<button type="button" name="edit" id="'.$transaksi->id.'" class="edit btn btn-warning btn-xs">EDIT</button>&nbsp;<button type="button" name="delete" id="'.$transaksi->id.'" class="delete btn btn-danger btn-xs">Hapus</button></center>';
			} else {
				$button = '<center><button type="button" name="detail" id="'.$transaksi->id.'" class="detail btn btn-secondary btn-xs">DETAIL</button></center>';
			}
				return $button;
		})
		->editColumn('panzisda_status', function($transaksi){
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
		->editColumn('jumlah', function($transaksi){
			return format_uang_with_rp($transaksi->jumlah);
		})
		->editColumn('jenis_transaksi', function($transaksi){
			return strtoupper($transaksi->jenis_transaksi);
		})
		->rawColumns(['panzisda_status', 'aksi'])
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
	
	public function editLembaga($id)
	{
		$data = DB::table('lembaga')->where('lembaga.id', $id)->leftJoin('lembaga_khusus','lembaga.id','=','lembaga_khusus.id_lembaga')->leftJoin('wilayah','wilayah.id','=','lembaga_khusus.id_wilayah')->select('lembaga.id','lembaga.nama_lembaga','lembaga.jenis','lembaga.status', DB::raw('group_concat(wilayah.nama_wilayah SEPARATOR ",") as wilayah'), DB::raw('group_concat(lembaga_khusus.id_wilayah SEPARATOR ",") as id_wilayah'))->groupBy('lembaga.id','lembaga.nama_lembaga','lembaga.jenis','lembaga.status')->first();

		$data->nama_lembaga = strtoupper($data->nama_lembaga);
		
		return json_encode($data);
	}

	public function editPaket($id)
	{
		$data = PaketZakat::find($id);
		
		return json_encode($data);
	}

	public function updateLembaga(Request $request)
	{
		// dd($request->all());
		$data = Lembaga::find($request->id);
		if (empty($data)) {
			return response()->json(['errors' => [0 => 'Data not found !']]);
		}
		$data->nama_lembaga = $request->edit_lembaga;
		$data->jenis = $request->edit_jenis;
		$data->status = $request->edit_status;
		$data->save();

		//Delete Old ID
		$deleteKhusus = LembagaKhusus::where('id_lembaga', $data->id)->get();
		foreach ($deleteKhusus as $value) {
			$value->delete();
		}

		if ($request->edit_status == 'khusus') {
			foreach ($request->edit_wilayah as $item) {
				$khusus = new LembagaKhusus();
				$khusus->id_lembaga = $data->id;
				$khusus->id_wilayah = $item;
				$khusus->save();
			}
		}

		return response()->json(['success' => 'update success stored!']);
	}

	public function updatePaket(Request $request)
	{
		// dd($request->all());
		$data = PaketZakat::find($request->id);
		if (empty($data)) {
			return response()->json(['errors' => [0 => 'Data not found !']]);
		}
		$data->nama_paket_zakat = $request->edit_paket_zakat;
		$data->save();

		return response()->json(['success' => 'update success stored!']);
	}

	public function editProfil()
	{
		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
		$data['editUser']   = User::find(Auth::user()->id);

		return view('admin.panziswil.profil', compact('data'));
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
	
	public function getGroup()
	{
		$data['user']       = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']  = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_lazis']      = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']         = $this->user_panziswil->where('id', Auth::user()->id)->first();
		$data['user_panzisda']         = $this->user_panzisda->where('id', Auth::user()->id)->first();
		return view('admin.panziswil.group', compact('data'));
	}

	public function getDataGroup()
	{
		$group       = Group::all();

		return DataTables::of($group)
			->addIndexColumn()
			->addColumn('aksi', function($group) {
				$button = '<center><button type="button" name="edit" id="'.$group->id.'" class="edit btn btn-secondary btn-xs">Edit</button>&nbsp;<button type="button" name="delete" id="'.$group->id.'" class="delete btn btn-danger btn-xs">Hapus</button></center>';
				return $button;
			})
			->editColumn('target', function($group){
				return format_uang_with_rp($group->target);
			})
			->rawColumns(['aksi'])
			->make(true);
	}

	public function editGroup($id)
	{
		$group = Group::find($id);
		
		return json_encode($group);
	}

	public function updateGroup(Request $request)
	{
		// dd($request->all());
		$group = Group::find($request->id);
		if (empty($group)) {
			return response()->json(['errors' => [0 => 'Data not found !']]);
		}
		$group->target = str_replace('.', '', $request->target);
		$group->save();

		return response()->json(['success' => 'update success stored!']);
	}
	
	public function getLaporanDZ()
	{
		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();      
		return view('admin.panziswil.laporan_dz', compact('data'));
	}

	public function getLaporanValidasiLembaga()
	{
		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();      
		return view('admin.panziswil.laporan_validasi_lembaga', compact('data'));
	}

	public function getLaporanValidasiWilayah()
	{
		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();      
		return view('admin.panziswil.laporan_validasi_wilayah', compact('data'));
	}

	public function getLaporJenisZiswaf()
	{
		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();
		$data['wil']                = Wilayah::all();      
		return view('admin.panziswil.laporan_jenisziswaf', compact('data'));
	}

	public function getDataLaporanDZ()
	{
		$duta = DB::table('users')->where('users.deleted_at', NULL)->join('role','role.id_users','=','users.id')->whereNotIn('role.id_jabatan', [1, 2, 3, 6])->join('wilayah','wilayah.id','=','users.id_wilayah')->select(DB::raw('ROW_NUMBER() OVER(order by users.id_wilayah ASC) AS nomor'), 'users.id', 'users.no_punggung', 'users.nama', 'wilayah.nama_wilayah', DB::raw('group_concat(role.id_jabatan SEPARATOR ",") as id_jabatan'), DB::raw('group_concat(IF(role.id_atasan IS NULL, "null", role.id_atasan)) as id_atasan'))->groupBy('users.id','users.no_punggung','users.nama','wilayah.nama_wilayah')->orderBy('users.id_wilayah', 'ASC')->orderBy('users.nama', 'ASC')->get();

		$tmp = [];
		foreach ($duta as $item) {
			$dummy['no_punggung'] = $item->no_punggung;
			$dummy['duta_zakat'] = $item->nama;
			
			$jabatan = explode(',', $item->id_jabatan);
			$atasan = explode(',', $item->id_atasan);

			if (count($jabatan) == 0) {
				array_push($jabatan, "null");
				array_push($atasan, "null");
			}

			$dummy['manajer_group'] = '';
			$dummy['manajer_area'] = '';

			for ($i=0;$i<count($jabatan);$i++) {
				if ($jabatan[$i] == "5") {
					if ($atasan[$i] == "null") {
						$dummy['manajer_group'] = '';
					} else {
						$temp = User::where('id', $atasan[$i])->first();

						if($temp == null) {
							$dummy['manajer_group'] = '';
						} else {
							$dummy['manajer_group'] = $temp->nama;
							$role = DB::table('role')->where('role.id_users', $temp->id)->where('role.id_jabatan', 4)->first();
							if($role == null) {
								$dummy['manajer_area'] = null;
							} else {
								$dummy['manajer_area'] = User::where('id', $role->id_atasan)->pluck('nama');
							}
						}
					}
				} else if ($jabatan[$i] == "4") {
					if ($atasan[$i] == "null") {
						$dummy['manajer_area'] = '';
					} else {
						$temp = User::where('id', $atasan[$i])->first();

						if($temp == null) {
							$dummy['manajer_area'] = '';
						} else {
							$dummy['manajer_area'] = $temp->nama;
						}
					}
				}
			}

			$dummy['wilayah'] = $item->nama_wilayah;
			$tmp[] = $dummy;
		}

		$user = collect($tmp);

		return DataTables::of($user)
			->addIndexColumn()
			->make(true);
	}

	public function getDataLaporanWilayah()
	{
		$target       = DB::table('users')
					->where('users.deleted_at', NULL)
					->join('role','role.id_users','=','users.id')
					->where('role.id_jabatan', 5)
					->join('group','group.id','=','role.id_group')
					->join('wilayah','wilayah.id','=','users.id_wilayah')
					->select('users.id_wilayah', 'wilayah.nama_wilayah', DB::raw('CAST(SUM(group.target) as UNSIGNED) as target'))
					->groupBy('users.id_wilayah','wilayah.nama_wilayah')
					->orderBy('users.id_wilayah', 'ASC')
					->get();

		$realisasi  = DB::table('users')
					->where('users.deleted_at', NULL)
					->join('role','role.id_users','=','users.id')
					->where('role.id_jabatan', 5) 
					->leftJoin('transaksi','transaksi.id_users','=','users.id')
					->leftJoin('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
					->leftJoin('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
					->where('status_transaksi.panzisda_status', '!=', NULL)
					->select('users.id_wilayah', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as realisasi'))
					->groupBy('users.id_wilayah')
					->orderBy('users.id_wilayah', 'ASC')
					->get();
		
		$tmp = [];
		foreach($target as $item1) {
			$dummy['nama_wilayah'] = $item1->nama_wilayah;
			$dummy['target'] = $item1->target;
			$terkumpul = 0;
			$persen = 0;

			foreach($realisasi as $item2) {
				if ($item1->id_wilayah == $item2->id_wilayah and $item2->realisasi != null) {
					$terkumpul = $item2->realisasi;
					$persen = ROUND($item2->realisasi / $item1->target * 100, 2);
					break;
				} else {
					$terkumpul = 0;
					$persen = 0;
				}
			}
			$dummy['realisasi'] = $terkumpul;
			$dummy['persentase'] = $persen;

			$tmp[] = $dummy;
		}
		$data = collect($tmp);

		$data = $data->sortByDesc('realisasi');

		return DataTables::of($data)
			->addIndexColumn()
			->make(true);
	}

	public function getDataLaporanJenisZiswaf($id)
	{
		if ($id == 0) {
			$data   = DB::table('transaksi')
				->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
				->join('paketzakat','paketzakat.id','=','detail_transaksi.id_paket_zakat')
				->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
				->where('status_transaksi.lazis_status', '!=', NULL)
				->join('users','users.id','=','transaksi.id_users')
				->where('users.deleted_at', NULL)
				->join('role','role.id_users','=','users.id')->where('role.id_jabatan', 5)
				->join('lembaga','lembaga.id','=','transaksi.id_lembaga')
				->join('wilayah','wilayah.id','=','users.id_wilayah')
				->select('wilayah.nama_wilayah', 'lembaga.nama_lembaga', 'paketzakat.nama_paket_zakat', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
				->groupBy('wilayah.nama_wilayah', 'lembaga.nama_lembaga', 'paketzakat.nama_paket_zakat')
				->orderBy('wilayah.id', 'ASC')
				->orderBy('lembaga.id', 'ASC')
				->orderBy('paketzakat.id', 'ASC')
				->get();
		} else {
			$data   = DB::table('transaksi')
				->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
				->join('paketzakat','paketzakat.id','=','detail_transaksi.id_paket_zakat')
				->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
				->where('status_transaksi.lazis_status', '!=', NULL)
				->join('users','users.id','=','transaksi.id_users')
				->where('users.deleted_at', NULL)
				->join('role','role.id_users','=','users.id')->where('role.id_jabatan', 5)
				->join('lembaga','lembaga.id','=','transaksi.id_lembaga')
				->join('wilayah','wilayah.id','=','users.id_wilayah')
				->where('wilayah.id', $id)
				->select('wilayah.nama_wilayah', 'lembaga.nama_lembaga', 'paketzakat.nama_paket_zakat', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
				->groupBy('wilayah.nama_wilayah', 'lembaga.nama_lembaga', 'paketzakat.nama_paket_zakat')
				->orderBy('wilayah.id', 'ASC')
				->orderBy('lembaga.id', 'ASC')
				->orderBy('paketzakat.id', 'ASC')
				->get();
		}

		return DataTables::of($data)
			->addIndexColumn()
			->editColumn('nama_lembaga', function($data){
				return strtoupper($data->nama_lembaga);
			})
			->make(true);
	}

	public function getDataLaporanValidasiWilayah()
	{
		$wilayah    = Wilayah::all();

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
			$tmp['persentase']  = ($valid_lz != 0) ? number_format(($valid_lz / $total) * 100, 2) : 0;
			$tmp['realisasi']  = ($valid_lz != 0) ? number_format(($valid_lz / $target->target) * 100, 2) : 0;
			$dummy[]            = $tmp;
		}
		$data = collect($dummy);

		return DataTables::of($data)
			->addIndexColumn()
			->make(true);
	}

	public function getDataLaporanValidasiLembaga()
	{
		$lembaga = Lembaga::all();

		$dummy   = [];
		foreach($lembaga as $value) {
			$transaksi  = DB::table('users')
						->where('users.deleted_at', NULL)
						->join('role','role.id_users','=','users.id')
						->where('role.id_jabatan', 5)
						->join('transaksi','transaksi.id_users','=','users.id')
						->where('transaksi.id_lembaga', $value->id)
						->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
						->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
						->select('status_transaksi.manajer_status', 'status_transaksi.panzisda_status', 'status_transaksi.lazis_status', DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
						->groupBy('status_transaksi.manajer_status', 'status_transaksi.panzisda_status', 'status_transaksi.lazis_status')
						->get();
			
			$tmp['nama_lembaga'] = $value->nama_lembaga;

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
			$tmp['persentase']  = ($valid_lz != 0) ? number_format(($valid_lz / $total) * 100, 2) : 0;
			$dummy[]            = $tmp;
		}
		$data = collect($dummy);

		return DataTables::of($data)
			->addIndexColumn()
			->editColumn('nama_lembaga', function($data){
				return strtoupper($data['nama_lembaga']);
			})
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
		$data['wil']                = Wilayah::all();

		return view('admin.panziswil.laporan_realisasi_paket_ziswaf', compact('data'));
	}

	public function getDataLaporanRealisasiPaketZiswaf($id)
	{
		$paket      = PaketZakat::all();

		if ($id == 0) {
			$wilayah    = Wilayah::all();
			$temp = [];
			foreach($wilayah as $item1) {
				//get wilayah
				$dummy['wilayah'] = $item1->nama_wilayah;

				foreach($paket as $item2) {
					$transaksi1  = DB::table('transaksi')
								->leftJoin('users','users.id','=','transaksi.id_users')
								->where('users.id_wilayah', $item1->id)
								->where('users.deleted_at', NULL)
								->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
								->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
								->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
								->where('transaksi.id_lembaga', $item2->id)
								->where('status_transaksi.lazis_status', '!=', NULL)
								->first();
					if ($transaksi1->jumlah == NULL) {
						$transaksi1->jumlah = 0;
					}

					//get paket
					$dummy['paket'] = $item2->nama_paket_zakat;

					$lembaga    = DB::table('lembaga')
								->leftJoin('lembaga_khusus','lembaga.id','=','lembaga_khusus.id_lembaga')
								->leftJoin('wilayah','wilayah.id','=','lembaga_khusus.id_wilayah')
								->select('lembaga.*')
								->where('lembaga_khusus.id_wilayah', $item1->id)
								->orWhere('lembaga_khusus.id_lembaga', NULL)
								->orderBy('lembaga.id', 'ASC')
								->get();

					$count = 1;
					$total = 0;
					foreach($lembaga as $item3) {

						$name = '';
						if ($item3->nama_lembaga == 'IZI' OR $item3->nama_lembaga == 'izi' or $item3->nama_lembaga == 'Izi') {
							$name = 'izi';
						} else if ($item3->nama_lembaga == 'LAZDAI' OR $item3->nama_lembaga == 'lazdai' or $item3->nama_lembaga == 'Lazdai') {
							$name = 'lazdai';
						} else if ($item3->nama_lembaga == 'DANA MANDIRI' OR $item3->nama_lembaga == 'dana mandiri' or $item3->nama_lembaga == 'Dana Mandiri') {
							$name = 'dana_mandiri';
						} else {
							$name = 'yayasan';
						}

						$transaksi2   = DB::table('transaksi')
									->leftJoin('users','users.id','=','transaksi.id_users')
									->where('users.id_wilayah', $item1->id)
									->where('users.deleted_at', NULL)
									->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
									->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
									->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
									->where('detail_transaksi.id_paket_zakat', $item2->id)
									->where('transaksi.id_lembaga', $item3->id)
									->where('status_transaksi.lazis_status', '!=', NULL)
									->first();

						if ($transaksi2->jumlah == NULL) {
							$transaksi2->jumlah = 0;
						}
						$dummy[$name] = $transaksi2->jumlah;
						$total = $total + $dummy[$name];
						$count = $count+1;
					}

					if(empty($dummy['yayasan'])) {
						$dummy['yayasan'] = 0;
					}

					$dummy['jumlah'] = $total;
					$temp[] = $dummy;
				}
			}
		} else {
			$wilayah    = Wilayah::where('id', $id)->get();
			$temp = [];
			foreach($wilayah as $item1) {
				//get wilayah
				$dummy['wilayah'] = $item1->nama_wilayah;

				foreach($paket as $item2) {
					$transaksi1  = DB::table('transaksi')
								->leftJoin('users','users.id','=','transaksi.id_users')
								->where('users.id_wilayah', $item1->id)
								->where('users.deleted_at', NULL)
								->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
								->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
								->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
								->where('transaksi.id_lembaga', $item2->id)
								->where('status_transaksi.lazis_status', '!=', NULL)
								->first();
					if ($transaksi1->jumlah == NULL) {
						$transaksi1->jumlah = 0;
					}

					//get paket
					$dummy['paket'] = $item2->nama_paket_zakat;

					$lembaga    = DB::table('lembaga')
								->leftJoin('lembaga_khusus','lembaga.id','=','lembaga_khusus.id_lembaga')
								->leftJoin('wilayah','wilayah.id','=','lembaga_khusus.id_wilayah')
								->select('lembaga.*')
								->where('lembaga_khusus.id_wilayah', $item1->id)
								->orWhere('lembaga_khusus.id_lembaga', NULL)
								->orderBy('lembaga.id', 'ASC')
								->get();

					$count = 1;
					$total = 0;
					foreach($lembaga as $item3) {

						$name = '';
						if ($item3->nama_lembaga == 'IZI' OR $item3->nama_lembaga == 'izi' or $item3->nama_lembaga == 'Izi') {
							$name = 'izi';
						} else if ($item3->nama_lembaga == 'LAZDAI' OR $item3->nama_lembaga == 'lazdai' or $item3->nama_lembaga == 'Lazdai') {
							$name = 'lazdai';
						} else if ($item3->nama_lembaga == 'DANA MANDIRI' OR $item3->nama_lembaga == 'dana mandiri' or $item3->nama_lembaga == 'Dana Mandiri') {
							$name = 'dana_mandiri';
						} else {
							$name = 'yayasan';
						}

						$transaksi2   = DB::table('transaksi')
									->leftJoin('users','users.id','=','transaksi.id_users')
									->where('users.id_wilayah', $item1->id)
									->where('users.deleted_at', NULL)
									->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
									->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
									->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
									->where('detail_transaksi.id_paket_zakat', $item2->id)
									->where('transaksi.id_lembaga', $item3->id)
									->where('status_transaksi.lazis_status', '!=', NULL)
									->first();

						if ($transaksi2->jumlah == NULL) {
							$transaksi2->jumlah = 0;
						}
						$dummy[$name] = $transaksi2->jumlah;
						$total = $total + $dummy[$name];
						$count = $count+1;
					}

					if(empty($dummy['yayasan'])) {
						$dummy['yayasan'] = 0;
					}

					$dummy['jumlah'] = $total;
					$temp[] = $dummy;
				}
			}
		}
		$data = $temp;

		return DataTables::of($data)
			->addIndexColumn()
			->make(true);
	}

	public function getLaporanRealisasiDistribusi()
	{
		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();
		$data['wil']                = Wilayah::all();

		return view('admin.panziswil.laporan_distribusi', compact('data'));
	}

	public function getDataLaporanRealisasiDistribusi($id)
	{
		$paket      = DB::table('paketzakat')->join('distribusi', 'distribusi.id_paket_zakat','=','paketzakat.id')->select('paketzakat.*')->orderBy('paketzakat.id', 'ASC')->get();

		if ($id == 0) {
			$wilayah    = Wilayah::all();

			$temp = [];
			foreach($wilayah as $wil) {

				foreach($paket as $item1) {
					$dummy['wilayah'] = $wil->nama_wilayah;
					$dummy['paket'] = $item1->nama_paket_zakat;
					$jumlah     = DB::table('transaksi')
								->join('users','users.id','=','transaksi.id_users')
								->where('users.id_wilayah', $wil->id)
								->where('users.deleted_at', NULL)
								->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
								->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
								->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
								->where('detail_transaksi.id_paket_zakat', $item1->id)
								->where('transaksi.id_lembaga', '!=', function($query) {
									$query->select('id')->from('lembaga')->whereIn('nama_lembaga', ['dana mandiri', 'Dana Mandiri', 'DANA MANDIRI'])->first();
								})
								->where('status_transaksi.lazis_status', '!=', NULL)
								->first();
					
					$distribusi     = Distribusi::where('id_paket_zakat', $item1->id)->get();
					
					foreach($distribusi as $dis) {
						$dummy['panzisnas'] = ($jumlah->jumlah != NULL) ? round(($dis->panzisnas * $jumlah->jumlah) / 100) : 0;
						$dummy['panziswil'] = ($jumlah->jumlah != NULL) ? round(($dis->panziswil * $jumlah->jumlah) / 100) : 0;
						$dummy['panzisda'] = ($jumlah->jumlah != NULL) ? round(($dis->panzisda * $jumlah->jumlah) / 100) : 0;

						$lembaga    = DB::table('lembaga')
									->leftJoin('lembaga_khusus','lembaga.id','=','lembaga_khusus.id_lembaga')
									->select('lembaga.*')
									->where('lembaga_khusus.id_wilayah', $wil->id)
									->orWhere('lembaga_khusus.id_lembaga', NULL)
									->orderBy('lembaga.id', 'ASC')
									->get();

						$total = 0;
						foreach($lembaga as $item3) {
							$name = '';
							if ($item3->nama_lembaga == 'IZI' OR $item3->nama_lembaga == 'izi' or $item3->nama_lembaga == 'Izi') {
								$name = 'izi';
							} else if ($item3->nama_lembaga == 'LAZDAI' OR $item3->nama_lembaga == 'lazdai' or $item3->nama_lembaga == 'Lazdai') {
								$name = 'lazdai';
							} else if ($item3->nama_lembaga == 'DANA MANDIRI' OR $item3->nama_lembaga == 'dana mandiri' or $item3->nama_lembaga == 'Dana Mandiri') {
								$name = 'dana_mandiri';
							} else {
								$name = 'yayasan';
							}

							$transaksi  = DB::table('transaksi')
										->join('users','users.id','=','transaksi.id_users')
										->where('users.id_wilayah', $wil->id)
										->where('users.deleted_at', NULL)
										->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
										->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
										->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
										->where('detail_transaksi.id_paket_zakat', $item1->id)
										->where('transaksi.id_lembaga', $item3->id)
										->where('status_transaksi.lazis_status', '!=', NULL)
										->first();
							
							if($name == 'dana_mandiri') {
								$dummy[$name] = ($transaksi->jumlah != NULL) ? $transaksi->jumlah : 0;
							} else {
								$dummy[$name] = ($transaksi->jumlah != NULL) ? round(($dis->mitra_strategis * $transaksi->jumlah) / 100) : 0;
							}
							$total = $total + $dummy[$name];
						}

						if(empty($dummy['yayasan'])) {
							$dummy['yayasan'] = 0;
						}
					}
					$dummy['jumlah'] = $dummy['panzisnas'] + $dummy['panziswil'] + $dummy['panzisda'] + $total;
					$temp[] = $dummy;
				}
			}
			$data = $temp;
		} else {
			$wilayah    = Wilayah::where('id', $id)->get();

			$temps = [];
			foreach($wilayah as $wil) {
				$dummys['wilayah'] = $wil->nama_wilayah;

				foreach($paket as $item1) {
					$dummys['paket'] = $item1->nama_paket_zakat;
					$jumlah     = DB::table('transaksi')
								->join('users','users.id','=','transaksi.id_users')
								->where('users.id_wilayah', $wil->id)
								->where('users.deleted_at', NULL)
								->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
								->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
								->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
								->where('detail_transaksi.id_paket_zakat', $item1->id)
								->where('transaksi.id_lembaga', '!=', function($query) {
									$query->select('id')->from('lembaga')->whereIn('nama_lembaga', ['dana mandiri', 'Dana Mandiri', 'DANA MANDIRI'])->first();
								})
								->where('status_transaksi.lazis_status', '!=', NULL)
								->first();
					
					$distribusi     = Distribusi::where('id_paket_zakat', $item1->id)->get();
					
					foreach($distribusi as $dis) {
						$dummys['panzisnas'] = ($jumlah->jumlah != NULL) ? round(($dis->panzisnas * $jumlah->jumlah) / 100) : 0;
						$dummys['panziswil'] = ($jumlah->jumlah != NULL) ? round(($dis->panziswil * $jumlah->jumlah) / 100) : 0;
						$dummys['panzisda'] = ($jumlah->jumlah != NULL) ? round(($dis->panzisda * $jumlah->jumlah) / 100) : 0;

						$lembaga    = DB::table('lembaga')
									->leftJoin('lembaga_khusus','lembaga.id','=','lembaga_khusus.id_lembaga')
									->select('lembaga.*')
									->where('lembaga_khusus.id_wilayah', $wil->id)
									->orWhere('lembaga_khusus.id_lembaga', NULL)
									->orderBy('lembaga.id', 'ASC')
									->get();

						$totals = 0;
						foreach($lembaga as $item3) {
							$name = '';
							if ($item3->nama_lembaga == 'IZI' OR $item3->nama_lembaga == 'izi' or $item3->nama_lembaga == 'Izi') {
								$name = 'izi';
							} else if ($item3->nama_lembaga == 'LAZDAI' OR $item3->nama_lembaga == 'lazdai' or $item3->nama_lembaga == 'Lazdai') {
								$name = 'lazdai';
							} else if ($item3->nama_lembaga == 'DANA MANDIRI' OR $item3->nama_lembaga == 'dana mandiri' or $item3->nama_lembaga == 'Dana Mandiri') {
								$name = 'dana_mandiri';
							} else {
								$name = 'yayasan';
							}

							$transaksi  = DB::table('transaksi')
										->join('users','users.id','=','transaksi.id_users')
										->where('users.id_wilayah', $wil->id)
										->where('users.deleted_at', NULL)
										->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
										->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
										->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
										->where('detail_transaksi.id_paket_zakat', $item1->id)
										->where('transaksi.id_lembaga', $item3->id)
										->where('status_transaksi.lazis_status', '!=', NULL)
										->first();
							
							$dummys[$name] = ($transaksi->jumlah != NULL) ? round(($dis->mitra_strategis * $transaksi->jumlah) / 100) : 0;
							$totals = $totals + $dummys[$name];
						}

						if(empty($dummys['yayasan'])) {
							$dummys['yayasan'] = 0;
						}
					}
					$dummys['jumlah'] = $dummys['panzisnas'] + $dummys['panziswil'] + $dummys['panzisda'] + $totals;
					$temps[] = $dummys;
				}
			}
			$data = $temps;
		}

		return DataTables::of($data)
			->addIndexColumn()
			->make(true);
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
		$donatur   			= DB::table('donatur')->leftJoin('transaksi','transaksi.id_donatur','=','donatur.id')->select('donatur.id','donatur.nama')->orderBy('transaksi.id_donatur', 'ASC')->get();
		$data['donatur']	= $donatur->unique('nama', 'alamat');

		return view('admin.panziswil.edit_transaksi', compact('data'));
	}

	public function getRekening($id)
	{
		$data = RekeningLembaga::where('id_lembaga', $id)->get();
		return json_encode($data);
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
		//Jenis Transaksi
		if ($request->jenis_transaksi != $nontunai->id) {
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
		if(ucwords($request->jenis_transaksi) == $barangx->id) {
			$barang                     = new Barang();
			$barang->id_transaksi       = $transaksi->id;
			$barang->nama_barang        = strtolower($request->nama_barang);
			$barang->save();
		}

		//STATUS TRANSAKSI
		$status = StatusTransaksi::where('id_transaksi', $transaksi->id)->first();
		if ($status->lazis_status == NULL) {
			$status->manajer_status = NULL;
			$status->panzisda_status = NULL;
			$status->lazis_status = NULL;
			$status->komentar = NULL;
			$status->save();
		}

		return back()->with(['success' => 'Data berhasil tersimpan!']);
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

	public function getStatus($id)
	{
		$data = Transaksi::find($id);

		return json_encode($data);
	}

	public function updateStatus(Request $request)
	{
		$status = StatusTransaksi::where('id_transaksi', $request->idTrx2)->first();
		$status->manajer_status = null;
		$status->panzisda_status = null;
		$status->lazis_status = null;
		$status->updated_at = null;
		$status->komentar = $request->komentar;
		$status->save();
	}

	public function getLaporanDonatur()
	{
		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();
		$data['wil']				= Wilayah::all();

		return view('admin.panziswil.laporan_donatur', compact('data'));
	}

	public function getDataLaporanDonatur($id)
	{
		if ($id == 0) {
			$data = 	DB::table('donatur')
						->leftJoin('transaksi','transaksi.id_donatur','=','donatur.id')
						->leftJoin('users','users.id','=','transaksi.id_users')
						->select('donatur.*')
						->whereNotNull('transaksi.id')
						->orWhereIn('donatur.nama', function($query) {
							$query->select('nama')->from('users')->get();
						})
						->orderBy('donatur.id', 'ASC')
						->get();
			$data = $data->unique('id_donatur');
		} else {
			$data = 	DB::table('donatur')
						->leftJoin('transaksi','transaksi.id_donatur','=','donatur.id')
						->leftJoin('users','users.id','=','transaksi.id_users')
						->select('donatur.*')
						->whereIn('donatur.nama', function($query) use ($id) {
							$query->select('nama')->from('users')->where('id_wilayah', $id)->get();
						})
						->orWhereIn('transaksi.id_users', function($query) use ($id) {
							$query->select('id')->from('users')->where('id_wilayah', $id)->get();
						})
						->orderBy('donatur.id', 'ASC')
						->get();
			$data = $data->unique('id_donatur');
		}

		return DataTables::of($data)
			->addIndexColumn()
			->make(true);
	}

	public function getLaporanRealisasiDutaZakat()
	{
		$data['user']               = $this->user->where('id', Auth::user()->id)->first();
		$data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
		$data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
		$data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
		$data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
		$data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
		$data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();
		$data['wil']                = Wilayah::all();

		return view('admin.panziswil.laporan_realisasi_dutazakat', compact('data'));
	}

	public function getDataLaporanRealisasiDutaZakat($id)
	{
		$paket      = PaketZakat::all();

		if ($id == 0) {
			$wilayah    = Wilayah::all();
			$temp = [];
			foreach($wilayah as $item1) {
				//get wilayah
				$dummy['wilayah'] = $item1->nama_wilayah;

				foreach($paket as $item2) {
					$transaksi1  = DB::table('transaksi')
								->leftJoin('users','users.id','=','transaksi.id_users')
								->where('users.id_wilayah', $item1->id)
								->where('users.deleted_at', NULL)
								->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
								->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
								->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
								->where('transaksi.id_lembaga', $item2->id)
								->first();
					if ($transaksi1->jumlah == NULL) {
						$transaksi1->jumlah = 0;
					}

					//get paket
					$dummy['paket'] = $item2->nama_paket_zakat;

					$lembaga    = DB::table('lembaga')
								->leftJoin('lembaga_khusus','lembaga.id','=','lembaga_khusus.id_lembaga')
								->leftJoin('wilayah','wilayah.id','=','lembaga_khusus.id_wilayah')
								->select('lembaga.*')
								->where('lembaga_khusus.id_wilayah', $item1->id)
								->orWhere('lembaga_khusus.id_lembaga', NULL)
								->orderBy('lembaga.id', 'ASC')
								->get();

					$count = 1;
					$total = 0;
					foreach($lembaga as $item3) {

						$name = '';
						if ($item3->nama_lembaga == 'IZI' OR $item3->nama_lembaga == 'izi' or $item3->nama_lembaga == 'Izi') {
							$name = 'izi';
						} else if ($item3->nama_lembaga == 'LAZDAI' OR $item3->nama_lembaga == 'lazdai' or $item3->nama_lembaga == 'Lazdai') {
							$name = 'lazdai';
						} else if ($item3->nama_lembaga == 'DANA MANDIRI' OR $item3->nama_lembaga == 'dana mandiri' or $item3->nama_lembaga == 'Dana Mandiri') {
							$name = 'dana_mandiri';
						} else {
							$name = 'yayasan';
						}

						$transaksi2   = DB::table('transaksi')
									->leftJoin('users','users.id','=','transaksi.id_users')
									->where('users.id_wilayah', $item1->id)
									->where('users.deleted_at', NULL)
									->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
									->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
									->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
									->where('detail_transaksi.id_paket_zakat', $item2->id)
									->where('transaksi.id_lembaga', $item3->id)
									->first();

						if ($transaksi2->jumlah == NULL) {
							$transaksi2->jumlah = 0;
						}
						$dummy[$name] = $transaksi2->jumlah;
						$total = $total + $dummy[$name];
						$count = $count+1;
					}

					if(empty($dummy['yayasan'])) {
						$dummy['yayasan'] = 0;
					}

					$dummy['jumlah'] = $total;
					$temp[] = $dummy;
				}
			}
		} else {
			$wilayah    = Wilayah::where('id', $id)->get();
			$temp = [];
			foreach($wilayah as $item1) {
				//get wilayah
				$dummy['wilayah'] = $item1->nama_wilayah;

				foreach($paket as $item2) {
					$transaksi1  = DB::table('transaksi')
								->leftJoin('users','users.id','=','transaksi.id_users')
								->where('users.id_wilayah', $item1->id)
								->where('users.deleted_at', NULL)
								->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
								->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
								->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
								->where('transaksi.id_lembaga', $item2->id)
								->first();
					if ($transaksi1->jumlah == NULL) {
						$transaksi1->jumlah = 0;
					}

					//get paket
					$dummy['paket'] = $item2->nama_paket_zakat;

					$lembaga    = DB::table('lembaga')
								->leftJoin('lembaga_khusus','lembaga.id','=','lembaga_khusus.id_lembaga')
								->leftJoin('wilayah','wilayah.id','=','lembaga_khusus.id_wilayah')
								->select('lembaga.*')
								->where('lembaga_khusus.id_wilayah', $item1->id)
								->orWhere('lembaga_khusus.id_lembaga', NULL)
								->orderBy('lembaga.id', 'ASC')
								->get();

					$count = 1;
					$total = 0;
					foreach($lembaga as $item3) {

						$name = '';
						if ($item3->nama_lembaga == 'IZI' OR $item3->nama_lembaga == 'izi' or $item3->nama_lembaga == 'Izi') {
							$name = 'izi';
						} else if ($item3->nama_lembaga == 'LAZDAI' OR $item3->nama_lembaga == 'lazdai' or $item3->nama_lembaga == 'Lazdai') {
							$name = 'lazdai';
						} else if ($item3->nama_lembaga == 'DANA MANDIRI' OR $item3->nama_lembaga == 'dana mandiri' or $item3->nama_lembaga == 'Dana Mandiri') {
							$name = 'dana_mandiri';
						} else {
							$name = 'yayasan';
						}

						$transaksi2   = DB::table('transaksi')
									->leftJoin('users','users.id','=','transaksi.id_users')
									->where('users.id_wilayah', $item1->id)
									->where('users.deleted_at', NULL)
									->join('detail_transaksi','detail_transaksi.id_transaksi','=','transaksi.id')
									->join('status_transaksi','status_transaksi.id_transaksi','=','transaksi.id')
									->select(DB::raw('CAST(SUM(detail_transaksi.jumlah) as UNSIGNED) as jumlah'))
									->where('detail_transaksi.id_paket_zakat', $item2->id)
									->where('transaksi.id_lembaga', $item3->id)
									->first();

						if ($transaksi2->jumlah == NULL) {
							$transaksi2->jumlah = 0;
						}
						$dummy[$name] = $transaksi2->jumlah;
						$total = $total + $dummy[$name];
						$count = $count+1;
					}

					if(empty($dummy['yayasan'])) {
						$dummy['yayasan'] = 0;
					}

					$dummy['jumlah'] = $total;
					$temp[] = $dummy;
				}
			}
		}
		$data = $temp;

		return DataTables::of($data)
			->addIndexColumn()
			->make(true);
	}
}
