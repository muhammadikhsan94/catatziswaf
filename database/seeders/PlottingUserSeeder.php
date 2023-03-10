<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class PlottingUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = fopen(base_path("database/seeders/docs/plotting.csv"), "r");
  
        $firstline = true;
        $no = 1;
        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                $user = User::where('no_punggung', $data[0])->first();
                if($user!=null) {
                    $manajer_group = User::where('no_punggung', $data[3])->first();
                    if($data[2]=="") {
                        Role::where('id_users', $user->id)->where('id_jabatan', $data[1])->update(
                            [
                                'id_atasan' => $manajer_group->id
                            ]
                        );
                    } else {
                        Role::where('id_users', $user->id)->where('id_jabatan', $data[1])->update(
                            [
                                'id_atasan' => $manajer_group->id
                            ]
                        );
                        $manajer_area = User::where('no_punggung', $data[4])->first();
                        Role::where('id_users', $user->id)->where('id_jabatan', $data[2])->update(
                            [
                                'id_atasan' => $manajer_area->id
                            ]
                        );
                    }
                }
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
