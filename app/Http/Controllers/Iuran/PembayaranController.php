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
}
