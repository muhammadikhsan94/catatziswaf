<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Mail\MailNotify;
use Mail;
use App\Jobs\SendMailJob;

class UserImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function collection(Collection $rows)
    {
        
        foreach ($rows as $row)
        {
            if($row['email'] == NULL) {
                break;
            } else {
                // dd($row);
                $user = User::create([
                    'no_punggung' => str_pad((int) $row['wilayah'], 2, '0', STR_PAD_LEFT).str_pad(count(User::all())+1, 4, 0, STR_PAD_LEFT),
                    'nama' => $row['nama'],
                    'alamat' => $row['alamat'],
                    'npwp' => null,
                    'no_hp' => $row['no_hp'],
                    'email' =>  $row['email'],
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'id_wilayah' => $row['wilayah']
                ]);
    
                if($row['id_jabatan_duta'] != null) {
                    Role::create([
                        'id_users' => $user->id,
                        'id_jabatan' => $row['id_jabatan_duta'],
                        'id_group' => $row['group']
                    ]);
                };
    
                if($row['id_jabatan_manajer_group'] != null) {
                    Role::create([
                        'id_users' => $user->id,
                        'id_jabatan' => $row['id_jabatan_manajer_group'],
                    ]);
                };
    
                if($row['id_jabatan_manajer_area'] != null) {
                    Role::create([
                        'id_users' => $user->id,
                        'id_jabatan' => $row['id_jabatan_manajer_area'],
                    ]);
                };
    
                if($row['id_jabatan_panzisda'] != null) {
                    Role::create([
                        'id_users' => $user->id,
                        'id_jabatan' => $row['id_jabatan_panzisda'],
                    ]);
                };
    
                if($row['id_jabatan_panziswil'] != null) {
                    Role::create([
                        'id_users' => $user->id,
                        'id_jabatan' => $row['id_jabatan_panziswil'],
                    ]);
                };
    
                if($row['id_jabatan_lazis'] != null) {
                    Role::create([
                        'id_users' => $user->id,
                        'id_jabatan' => $row['id_jabatan_lazis'],
                        'id_lembaga' => $row['lembaga'],
                    ]);
                };
                
                // Mail::to($user->email)->send(new MailNotify($user));
                dispatch(new SendMailJob($user->email, new MailNotify($user)));
            }
            
        }
        
        // return $user;

    }
}
