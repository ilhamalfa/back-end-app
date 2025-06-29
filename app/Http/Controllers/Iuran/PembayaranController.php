<?php

namespace App\Http\Controllers\Iuran;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Penghuni;
use App\Models\Tagihan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function pembayaran($tagihan_id, Request $request){
        $tagihan = Tagihan::findOrFail($tagihan_id);

        $validate = $this->validate($request, [
                        'jumlah' => 'required|integer',
                        'keterangan' => 'required|string',
                    ]);

        if($tagihan->status == 'belum bayar'){
            $tagihan->update(['status' => 'lunas']);

            $pembayaran = Pembayaran::create([
                'tagihan_id' => $tagihan->id,
                'jumlah' => $validate['jumlah'],
                'keterangan' => $validate['keterangan'],
                'tanggal_bayar' => Carbon::now()->format('Y-m-d'),
            ]);
        }

        return response()->json([
            'message' => 'Data Pembayaran berhasil ditambahkan!',
            'data' => $pembayaran
        ], 201);
    }

    public function pembayaran_kebersihan($tagihan_id, Request $request){
        $tagihan = Tagihan::findOrFail($tagihan_id);
        $validate = $this->validate($request, [
                        'jumlah' => 'required|integer',
                    ]);
        $bayar = $validate['jumlah'] / $request->bulan;
        $pembayaran = null;

        if($tagihan->status == 'belum bayar'){
            $tagihan->update(['status' => 'lunas']);

            $pembayaran = Pembayaran::create([
                'tagihan_id' => $tagihan->id,
                'jumlah' => $bayar,
                'keterangan' => 'Tagihan Kebersihan' + $validate['keterangan'],
                'tanggal_bayar' => Carbon::now()->format('Y-m-d'),
            ]);
        }

        if ($request->bulan > 1) {
            $bulanIni = Carbon::now(); // ini Carbon object, bukan string

            for ($i = 1; $i < $request->bulan; $i++) {
                $bulanTagihan = $bulanIni->copy()->addMonths($i)->format('Y-m') . '-01';
                
                $tagihan_data = Tagihan::create([
                        'penghuni_id' => $tagihan->penghuni_id,
                        'bulan' => $bulanTagihan,
                        'status' => 'lunas',
                        'tipe' => 'kebersihan',
                        'jumlah' => 15000,
                ]);

                $pembayaran = Pembayaran::create([
                    'tagihan_id' => $tagihan_data->id,
                    'jumlah' => $bayar,
                    'keterangan' => 'Tagihan Kebersihan' + $request->keterangan,
                    'tanggal_bayar' => Carbon::now()->format('Y-m-d'),
                ]);
            }
        }

        return response()->json([
            'message' => 'Data Pembayaran berhasil ditambahkan!',
            'data' => $pembayaran
        ], 201);
    }

    public function pembayaran_satpam($tagihan_id, Request $request){
        $tagihan = Tagihan::findOrFail($tagihan_id);
        $pembayaran = null;

        if($tagihan->status == 'belum bayar'){
            $tagihan->update(['status' => 'lunas']);

            $pembayaran = Pembayaran::create([
                'tagihan_id' => $tagihan->id,
                'jumlah' => 100000,
                'keterangan' => 'Tagihan Satpam' . $request->keterangan,
                'tanggal_bayar' => Carbon::now()->format('Y-m-d'),
            ]);
        }

        return response()->json([
            'message' => 'Data Pembayaran berhasil ditambahkan!',
            'data' => $pembayaran
        ], 201);
    }
}
