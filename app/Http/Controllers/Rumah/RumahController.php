<?php

namespace App\Http\Controllers\Rumah;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\Penghuni;
use App\Models\PenghuniRumah;
use App\Models\Rumah;
use Illuminate\Http\Request;

class RumahController extends Controller
{
    public function store(Request $request){
        $validate = $this->validate($request, [
                        'nomor_rumah' => 'required|unique:rumahs,nomor_rumah',
                    ]);

        $rumah = Rumah::create($validate);
    
        return response()->json([
            'message' => 'Data Rumah berhasil ditambahkan!',
            'data' => $rumah
        ], 201);
    }

    public function update($id, Request $request){
        $data = Rumah::findOrFail($id);

        $validate = $this->validate($request, [
                        'nomor_rumah' => 'required|unique:rumahs,nomor_rumah,' . $data->id,
                    ]);


        $data->update(['nomor_rumah' => $validate['nomor_rumah']]);
    
        return response()->json([
            'message' => 'Data Rumah berhasil Diupdate!',
            'data' => $data
        ], 200);
    }

    public function get_data(){
        $rumah_datas = Rumah::all();
        $pemasukan_datas = Pembayaran::all();
        $pengeluaran_datas = Pengeluaran::all();

        return response()->json([
            'jml_rumah' => $rumah_datas->count(),
            'jml_pemasukan' => $pemasukan_datas->sum('jumlah'),
            'jml_pengeluaran' => $pengeluaran_datas->sum('jumlah'),
        ], 200);
    }

    public function get_rumah(){
        $rumah_datas = Rumah::all();
        $datas = [];
        $i = 0;
        $a = 1;

        foreach ($rumah_datas as $rumah) {
            $datas[$i] = [
                'no' => $a,
                'id' => $rumah->id,
                'nomor_rumah' => $rumah->nomor_rumah,
                'is_occupied' => $rumah->is_occupied,
            ];

            if($rumah->is_occupied == true){
                $datas[$i]['nama'] = $rumah->penghuni->sortByDesc('created_at')->first()->nama;
            } else{
                $datas[$i]['nama'] = null;
            }

            $a++;
            $i++;
        }

        return response()->json($datas, 200);
    }

    public function rumah_detail($id){
        $data = Rumah::findOrFail($id);

        return response()->json($data, 200);
    }


}
