<?php

namespace App\Http\Controllers\Iuran;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function get_data(){
        $datas = Pengeluaran::whereMonth('bulan', Carbon::now()->month)
                                ->get();

        return response()->json([
            'message' => 'Data Pengeluaran berhasil ditambahkan!',
            'datas' => $datas
        ], 201);
    }

    public function pengeluaran(Request $request){
        $validate = $this->validate($request, [
                        'jumlah' => 'required|integer',
                        'keterangan' => 'required|string',
                    ]);

        $pengeluaran = Pengeluaran::create([
                'jumlah' => $validate['jumlah'],
                'keterangan' => $validate['keterangan'],
                'bulan' => Carbon::now()->format('Y-m-d'),
            ]);

        return response()->json([
            'message' => 'Data Pengeluaran berhasil ditambahkan!',
            'data' => $pengeluaran
        ], 201);
    }
    
}
