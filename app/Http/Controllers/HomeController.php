<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\Wilayah;
use App\Models\Donatur;
use App\Models\Transaksi;
use App\Models\StatusTransaksi;
use DB;
use App\Exports\DaftarDZ;
use App\Exports\IziExport;
use App\Exports\LazdaiExport;
use App\Exports\StrukturExport;
use App\Exports\TunaiExport;
use App\Exports\NonTunaiExport;
use App\Exports\BarangExport;
use App\Exports\TransaksiExport;
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;
use View;
use Hash;
use Mail;
use PDF;
use App\Mail\UpdateProfilNotify;
use PhpOffice\PhpWord\TemplateProcessor;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        
        $this->user             = DB::table('users')
                                ->join('role','role.id_users','=','users.id')
                                // ->join('group','group.id','=','role.id_group')
                                ->leftJoin('wilayah','wilayah.id','=','users.id_wilayah')
                                ->select('users.*', 'role.id_jabatan', 'role.id_atasan', 'role.id_group', 'wilayah.nama_wilayah')
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = DB::table('role')->where('role.id_users', Auth::user()->id)->get()->toArray();
        
        foreach ($user as $role) {
            if ($role->id_jabatan != 5) {
                if($role->id_jabatan == 1){
                    return redirect()->to('/panziswil');
                }
                if($role->id_jabatan == 2){
                    return redirect()->to('/panzisda');
                }
                if($role->id_jabatan == 3){
                    return redirect()->to('/manajerarea');
                }
                if($role->id_jabatan == 4){
                    return redirect()->to('/manajer');
                }
                if($role->id_jabatan == 6){
                    return redirect()->to('/lazis');
                }
            }
        }

        foreach ($user as $role) {
            if ($role->id_jabatan == 5) {
                return redirect()->to('/duta');
            }
            if ($role->id_jabatan == null) {
                return redirect()->to('/user');
            }
        }
        
    }

    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return redirect('/login');
    }

    public function transaksiExport()
    {
        $id = Auth::user()->id_wilayah;
        return Excel::download(new TransaksiExport($id), 'Full Transaksi.xlsx');
    }

    public function iziExportById()
    {
        $id = Auth::user()->id_wilayah;
        return Excel::download(new IziExport($id), 'IZI.xlsx');
    }

    public function lazdaiByIdExport()
    {
        $id = Auth::user()->id_wilayah;
        return Excel::download(new LazdaiExport($id), 'LAZDAI.xlsx');
    }

    public function strukturByIdExport()
    {
        $id = Auth::user()->id_wilayah;
        return Excel::download(new StrukturExport($id), 'STRUKTURAL.xlsx');
    }

    public function tunaiByIdExport()
    {
        $id = Auth::user()->id_wilayah;
        return Excel::download(new TunaiExport($id), 'TUNAI.xlsx');
    }

    public function nontunauByIdExport()
    {
        $id = Auth::user()->id_wilayah;
        return Excel::download(new NonTunaiExport($id), 'NONTUNAI.xlsx');
    }

    public function barangByIdExport()
    {
        $id = Auth::user()->id_wilayah;
        return Excel::download(new BarangExport($id), 'Barang.xlsx');
    }

    public function userByIdExport($id)
    {
        $wilayah = Wilayah::find($id);
        return Excel::download(new UserExport($id), 'Data Duta Zakat_'.$wilayah->nama_wilayah.'.xlsx');
    }
    
    public function userExport()
    {
        return Excel::download(new DaftarDZ(), 'Data Semua Duta Zakat.xlsx');
    }
    
    public function profil()
    {
        $data['user']               = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();
        $data['editUser']           = User::find(Auth::user()->id);

        return view('profil', compact('data'));
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
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->new_password);
            }
        } else {
            $user->password = $user->password;
        }
        $user->save();

        Mail::to($user->email)->send(new UpdateProfilNotify($user));
    }

    public function kalkulator()
    {
        return view('kalkulator');
    }

    public function buatSuratTugas()
    {
        // $transaksi = Transaksi::where('id_lembaga', function($query) {
        //     $query->select('id')->from('lembaga')->whereIn('nama_lembaga', ['dana mandiri', 'Dana Mandiri', 'DANA MANDIRI'])->pluck('id');
        // })->get();

        // foreach ($transaksi as $item) {
        //     $status = StatusTransaksi::where('id_transaksi', $item->id)->first();

        //     if ($status->panzisda_status != NULL) {
        //         $status->lazis_status = $status->panzisda_status;
        //         $status->save();
        //     }
        // }
        // $user = DB::table('users')->join('role','role.id_users','=','users.id')->select('users.nama','users.alamat','users.no_hp','users.npwp','users.email')->where('role.id_jabatan', 5)->orderBy('users.id')->get();
        // $count = count(Donatur::all());
        // foreach($user as $item) {
        //     $data = new Donatur;
        //     $data->id_donatur = str_pad($count, 7, 0, STR_PAD_LEFT).'1';
        //     $data->nama = $item->nama;
        //     $data->alamat = ($item->alamat) ? $item->alamat : '-';
        //     $data->no_hp = $item->no_hp;
        //     $data->npwp = $item->npwp;
        //     $data->email = $item->email;
        //     $data->save();
        //     $count += 1;
        // }
        
        // $count = 1;
        // $donatur = Donatur::all();
        // foreach($donatur as $item) {
        //     $data = Donatur::find($item->id);
        //     $data->id_donatur = str_pad($count, 7, 0, STR_PAD_LEFT).'0';
        //     $data->alamat = ($item->alamat) ? $item->alamat : '-';
        //     $data->save();
        //     $count += 1;
        // }
        
        // $user = DB::table('users')->join('role','role.id_users','=','users.id')->select('users.*')->where('role.id_jabatan', 5)->where('users.id_wilayah', 16)->orderBy('users.id', 'ASC')->get();
        // $no_surat = 1062;
        // foreach($user as $item) {
        //     $no_surat += 1;
        //     $nama = $item->nama;
        //     $alamat = $item->alamat;
        //     $no_punggung = $item->no_punggung;
        //     $no_hp = $item->no_hp;

            //IZI
            // $izi = new TemplateProcessor(storage_path('IZI.docx'));
            // $izi->setValue('no_surat', str_pad($no_surat, 4, 0, STR_PAD_LEFT));
            // $izi->setValue('nama', $nama);
            // $izi->setValue('alamat', $alamat);
            // $izi->setValue('no_punggung', $no_punggung);
            // $izi->setValue('no_hp', $no_hp);

            // $filename_izi = $no_punggung.'_IZI.docx';

            // header('Content-Type: application/octet-stream');
            // header('Content-Disposition: attachment; filename="'.$filename_izi.'"');
            // $izi->saveAs(public_path('/surat_tugas/izi/'.$filename_izi));

            //LAZDAI
            // $lazdai = new TemplateProcessor(storage_path('LAZDAI.docx'));
            // $lazdai->setValue('no_surat', str_pad($no_surat, 4, 0, STR_PAD_LEFT));
            // $lazdai->setValue('nama', $nama);
            // $lazdai->setValue('no_punggung', $no_punggung);

            // $filename_lazdai = $no_punggung.'_LAZDAI.docx';

            // header('Content-Type: application/octet-stream');
            // header('Content-Disposition: attachment; filename="'.$filename_lazdai.'"');
            // $lazdai->saveAs(public_path('/surat_tugas/lazdai/'.$filename_lazdai));
        // }
    }
    
    public function informasi()
    {
        $data['user']               = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();
        $data['editUser']           = User::find(Auth::user()->id);

        return view('informasi', compact('data'));
    }
    
    public function faq()
    {
        $data['user']               = $this->user->where('id', Auth::user()->id)->first();
        $data['user_duta']          = $this->user_duta->where('id', Auth::user()->id)->first();
        $data['user_manajer']       = $this->user_manajer->where('id', Auth::user()->id)->first();
        $data['user_manajerarea']   = $this->user_manajerarea->where('id', Auth::user()->id)->first();
        $data['user_panzisda']      = $this->user_panzisda->where('id', Auth::user()->id)->first();
        $data['user_lazis']         = $this->user_lazis->where('id', Auth::user()->id)->first();
        $data['user_panziswil']     = $this->user_panziswil->where('id', Auth::user()->id)->first();
        $data['editUser']           = User::find(Auth::user()->id);

        return view('faq', compact('data'));
    }
    
    public function suratTugasIZI($id)
    {
        $alluser = DB::table('users')->join('role','role.id_users','=','users.id')->select('users.*')->where('role.id_jabatan', 5)->orderBy('users.id', 'ASC')->get();
        $data = User::where('id', $id)->first();
        $no_surat = 1;
        foreach($alluser as $user) {
            if ($user->id == $data->id) {
                break;
            } else {
                $no_surat = $no_surat + 1;
            }
        }
        $data->no_surat = $no_surat;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'dpi' => 85, 'defaultFont' => 'calibri'])->loadView('st_izi', compact('data'))->setPaper('a4', 'portrait');
        return $pdf->stream('surattugas.pdf');
    }
    
    public function suratTugasLAZDAI($id)
    {
        $alluser = DB::table('users')->join('role','role.id_users','=','users.id')->select('users.*')->where('role.id_jabatan', 5)->orderBy('users.id', 'ASC')->get();
        $data = User::where('id', $id)->first();
        $no_surat = 1;
        foreach($alluser as $user) {
            if ($user->id == $data->id) {
                break;
            } else {
                $no_surat = $no_surat + 1;
            }
        }
        $data->no_surat = $no_surat;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'dpi' => 85, 'defaultFont' => 'calibri'])->loadView('st_lazdai', compact('data'))->setPaper('a4', 'portrait');
        return $pdf->stream('surattugas.pdf');
    }
}
