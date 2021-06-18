<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wilayah;
use App\Models\Role;
use Hash;
use App\Mail\MailNotify;
use Mail;
use App\Mail\RegisterSuccess;

class RegisterController extends Controller
{
    public function daftar()
    {
        $data['wilayah'] = Wilayah::all();

        return view('beranda', compact('data'));
    }

    public function simpanRegister(Request $request)
    {
        $wilayah = Wilayah::where('id', $request->id_wilayah)->first();
        $nubrow = count(User::all())+1;

        $user = new User();
        $user->no_punggung = str_pad($wilayah->id, 2, '0', STR_PAD_LEFT).str_pad($nubrow, 4, 0, STR_PAD_LEFT);
        $user->nama = $request->nama;
        $user->alamat = $request->alamat;
        $user->npwp = $request->npwp;
        $user->no_hp = $request->no_hp;
        $user->id_wilayah = $request->id_wilayah;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $role = new Role();
        $role->id_users = $user->id;
        $role->save();
        
        Mail::to($user->email)->send(new RegisterSuccess($user));
        
        return redirect('/login')->with(['success' => 'Pendaftaran Berhasil, Silahkan Login!']);
    }
}
