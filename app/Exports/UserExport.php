<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use App\Models\User;

class UserExport implements FromCollection, WithHeadings
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
        $duta = DB::table('users')->join('role','role.id_users','=','users.id')->whereNotIn('role.id_jabatan', [1, 2, 3, 6])->join('wilayah','wilayah.id','=','users.id_wilayah')->where('users.id_wilayah', $this->id)->select(DB::raw('ROW_NUMBER() OVER(order by users.id_wilayah ASC) AS nomor'), 'users.id', 'users.no_punggung', 'users.nama', 'wilayah.nama_wilayah', DB::raw('group_concat(role.id_jabatan SEPARATOR ",") as id_jabatan'), DB::raw('group_concat(IF(role.id_atasan IS NULL, "null", role.id_atasan)) as id_atasan'))->groupBy('users.id','users.no_punggung','users.nama','wilayah.nama_wilayah')->orderBy('users.id_wilayah', 'ASC')->orderBy('users.id', 'ASC')->get();

        $tmp = [];
        foreach ($duta as $item) {
        	$dummy['no'] = $item->nomor;
        	$dummy['no_punggung'] = $item->no_punggung;
        	$dummy['duta_zakat'] = $item->nama;

        	$jabatan = explode(',', $item->id_jabatan);
        	$atasan = explode(',', $item->id_atasan);

        	if (count($jabatan) == 1) {
        		array_push($jabatan, "null");
        		array_push($atasan, "null");
        	} else if (count($jabatan) == 0) {
        		array_push($jabatan, "null");
        		array_push($atasan, "null");
        		array_push($jabatan, "null");
        		array_push($atasan, "null");
        	}

        	$dummy['manajergroup'] = '';
			$dummy['manajerarea'] = '';

        	for ($i=0;$i<count($jabatan);$i++) {
        		if ($jabatan[$i] == "5") {
        			if ($atasan[$i] == "null") {
        				$dummy['manajergroup'] = '';
        			} else {
	        			$temp = User::where('id', $atasan[$i])->first();
	        			if($temp == null) {
	        				$dummy['manajergroup'] = '';
	        			} else {
	        				$dummy['manajergroup'] = $temp->nama;
	        			}
	        		}
        		} else if ($jabatan[$i] == "4") {
        			if ($atasan[$i] == "null") {
        				$dummy['manajerarea'] = '';
        			} else {
	        			$temp = User::where('id', $atasan[$i])->first();
	        			if($temp == null) {
	        				$dummy['manajerarea'] = '';
	        			} else {
	        				$dummy['manajerarea'] = $temp->nama;
	        			}
	        		}
        		}
        	}

        	$dummy['wilayah'] = $item->nama_wilayah;
        	$tmp[] = $dummy;
        }

        $data = collect($tmp);

        return $data;
    }

    public function headings(): array
    {
        return [
        	'Nomor',
            'Nomor Punggung',
            'Nama Duta Zakat',
            'Nama Manajer Group',
            'Nama Manajer Area',
            'Wilayah'        
        ];
    }

    // public function collection()
    // {
    //     if ($this->id == NULL or $this->id == 0) {
    //         $user = DB::table('users')
    //                 ->leftJoin('wilayah','wilayah.id','=','users.id_wilayah')
    //                 ->join('role','role.id_users','=','users.id')
    //                 ->join('jabatan','jabatan.id','=','role.id_jabatan')
    //                 ->select('users.no_punggung', 'users.nama', 'users.alamat', 'users.no_hp', 'users.email', 'wilayah.nama_wilayah', 'jabatan.nama_jabatan', 'role.id_group')
    //                 ->get();
    //     } else {
    //         $user = DB::table('users')
    //                 ->join('wilayah','wilayah.id','=','users.id_wilayah')
    //                 ->join('role','role.id_users','=','users.id')
    //                 ->join('jabatan','jabatan.id','=','role.id_jabatan')
    //                 ->select('users.no_punggung', 'users.nama', 'users.alamat', 'users.no_hp', 'users.email', 'wilayah.nama_wilayah', 'jabatan.nama_jabatan', 'role.id_group')
    //                 ->where('users.id_wilayah', $this->id)
    //                 ->get();
    //     }
    //     return $user;
    // }

    // public function headings(): array
    // {
    //     return [
    //         'nomor punggung',
    //         'nama',
    //         'alamat',
    //         'nomor hp',
    //         'email',
    //         'asal kabupaten/kota',
    //         'jabatan',
    //         'group'
    //     ];
    // }
}
