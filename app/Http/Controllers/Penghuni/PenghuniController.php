<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use App\Models\Penghuni;
use App\Models\Rumah;
use App\Models\Tagihan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PenghuniController extends Controller
{
    public function store($id, Request $request){
        $validate = $this->validate($request, [
                        'nama' => 'required|string|max:255',
                        'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                        'status_penghuni' => 'required',
                        'no_telp' => ['required', 'string', 'regex:/^[0-9]+$/', 'min:10', 'max:15', 'unique:penghunis,no_telp'],
                        'is_married' => 'required|boolean'
                    ]);

        $extension = $request->file('foto_ktp')->extension();
        $fileName = $request->nama . '-' . now()->timestamp. '.' . $extension;

        $validate['foto_ktp'] = $request->file('foto_ktp')->storeAs('Penghuni/KTP', $fileName);
        $validate['rumah_id'] = $id;

        $penghuni = Penghuni::create($validate);

        $rumah_data = Rumah::findOrFail($id);
        $rumah_data->update([
            'is_occupied' => true
        ]);

        $bulanIni = Carbon::now()->format('Y-m-d');
        
        $tagihan = Tagihan::where('penghuni_id', $penghuni->id)
                            ->where('bulan', $bulanIni)
                            ->doesntExist();

        if($tagihan){
            Tagihan::create([
                'penghuni_id' => $penghuni->id,
                'bulan' => $bulanIni,
                'status' => 'belum bayar',
                'tipe' => 'kebersihan',
                'jumlah' => 15000,
            ]);

            Tagihan::create([
                'penghuni_id' => $penghuni->id,
                'bulan' => $bulanIni,
                'status' => 'belum bayar',
                'tipe' => 'satpam',
                'jumlah' => 100000,
            ]);
        }
    
        return response()->json([
            'message' => 'Data penghuni berhasil ditambahkan!',
            'data' => $penghuni
        ], 201);
    }

    public function update($id, Request $request){
        $data = Penghuni::findOrFail($id);

        if ($request->hasFile('foto_ktp')) {
            $validate = $this->validate($request, [
                        'nama' => 'required|string|max:255',
                        'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                        'status_penghuni' => 'required',
                        'no_telp' => ['required', 'string', 'regex:/^[0-9]+$/', 'min:10', 'max:15', 'unique:penghunis,no_telp,' . $data->id],
                        'is_married' => 'required|boolean'
                    ]);

            if ($data->foto_ktp && file_exists(public_path('storage/'.$data->foto_ktp))) {
                unlink(public_path('storage/'.$data->foto_ktp));
            }

            $extension = $request->file('foto_ktp')->extension();
            $fileName = $request->nama . '-' . now()->timestamp. '.' . $extension;

            $validate['foto_ktp'] = $request->file('foto_ktp')->storeAs('Penghuni/KTP', $fileName);
        }else{
            $validate = $this->validate($request, [
                            'nama' => 'required|string|max:255',
                            'status_penghuni' => 'required',
                            'no_telp' => ['required', 'string', 'regex:/^[0-9]+$/', 'min:10', 'max:15', 'unique:penghunis,no_telp,' . $data->id],
                            'is_married' => 'required|boolean'
                        ]);
        }
        
        $data->update($validate);
    
        return response()->json([
            'message' => 'Data penghuni berhasil diupdate!',
            'data' => $data
        ], 200);
    }

    public function remove($id){
        $penghuni_data = Penghuni::findOrFail($id);
        $rumah_data = Rumah::findorFail($penghuni_data->rumah_id);
        
        $rumah_data->update(['is_occupied' => false]);
        $penghuni_data->update(['is_aktif' => false]);

        return response()->json([
            'message' => 'Berhasil men-copot penghuni ke rumah!',
        ], 200);
    }

    public function get_penghuni(){
        $penghuni_datas = Penghuni::all();
        $datas = [];
        $i = 0;
        $a = 1;

        foreach ($penghuni_datas as $penghuni) {
            $datas[$i] = [
                'no' => $a,
                'id' => $penghuni->id,
                'nama' => $penghuni->nama,
                'foto_ktp' => $penghuni->foto_ktp,
                'status_penghuni' => $penghuni->status_penghuni,
                'no_telp' => $penghuni->no_telp,
                'is_married' => $penghuni->is_married,
                'is_aktif' => $penghuni->is_aktif,
            ];

            if($penghuni->has('rumah') && $penghuni->is_aktif == true){
                $datas[$i]['nomor_rumah'] = $penghuni->rumah->nomor_rumah;
            } else{
                $datas[$i]['nomor_rumah'] = null;
            }

            $a++;
            $i++;
        }

        return response()->json($datas, 200);
    }

    public function penghuni_detail($id){
        $penghuni = Penghuni::findOrFail($id);

        return response()->json($penghuni, 200);
    }
}
