<?php

namespace App\Http\Controllers\Iuran;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\Penghuni;
use App\Models\Tagihan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function get_Tagihan(){
        $penghuni_datas = Penghuni::where('is_aktif', true)->get();

        foreach ($penghuni_datas as $penghuni) {
            $penghuni['tagihan'] = Tagihan::where('penghuni_id', $penghuni->id)
                                        ->whereMonth('bulan', Carbon::now()->month)
                                        ->get();
            $penghuni['nomor_rumah'] = $penghuni->rumah->nomor_rumah;
        }

        return response()->json([$penghuni_datas], 200);
    }

    public function detail_Tagihan($id){
        $tagihan_datas = Tagihan::where('penghuni_id', $id)->get();

        return response()->json([$tagihan_datas], 200);
    }

    public function getRekapData(){
        $datas = [];
        for ($i = 1; $i <= 12; $i++) {
            if ($i < 10) {
                $bulan = '0' . $i;
            } else {
                $bulan = $i;
            }

            $datas[$bulan] = [
                'pemasukan' => 0,
                'pengeluaran' => 0,
                'sisa' => 0,
            ];
        }

        $pemasukanDatas = Pembayaran::whereYear('tanggal_bayar', Carbon::now()->year)
                                    ->get();
        $pengeluaranDatas = Pengeluaran::whereYear('bulan', Carbon::now()->year)
                                    ->get();
        
        foreach ($pemasukanDatas as $pemasukan) {
            $tanggal_bayar = Carbon::parse($pemasukan->tanggal_bayar);
            $bulan = $tanggal_bayar->format('m');

            $datas[$bulan]['pemasukan'] += $pemasukan->jumlah;
        }

        foreach ($pengeluaranDatas as $pengeluaran) {
            $tanggal_bayar = Carbon::parse($pengeluaran->tanggal_bayar);
            $bulan = $tanggal_bayar->format('m');

            $datas[$bulan]['pengeluaran'] += $pengeluaran->jumlah;
        }

        foreach ($datas as $key => $value) {
            $datas[$key]['sisa'] = $value['pemasukan'] - $value['pengeluaran'];
        }

        return response()->json($datas, 200);
    }

    public function getDetailData(Request $request){
        $bulan = isset($request->bulan) ? $request->bulan : Carbon::now()->month;
        $tahun = Carbon::now()->year;
        $transaksi_datas = [];
        $detail = [
            'pemasukan' => 0,
            'pengeluaran' => 0,
            'sisa' => 0,
        ];

        $pemasukanDatas = Pembayaran::whereYear('tanggal_bayar', $tahun)
                                    ->whereMonth('tanggal_bayar', $bulan)
                                    ->get();

        foreach ($pemasukanDatas as  $pemasukan) {
            $transaksi_datas[] = [
                'jumlah' => $pemasukan->jumlah,
                'keterangan' => $pemasukan->keterangan . " (Oleh: " . $pemasukan->tagihan->penghuni->nama ." - No Rumah: " . $pemasukan->tagihan->penghuni->rumah->nomor_rumah . ")",
                'jenis' => 'pemasukan',
                'tanggal' => $pemasukan->tanggal_bayar,
            ];
        }

        $pengeluaranDatas = Pengeluaran::whereYear('bulan', $tahun)
                                    ->whereMonth('bulan', $bulan)
                                    ->get();

        foreach ($pengeluaranDatas as  $pengeluaran) {
            $transaksi_datas[] = [
                'jumlah' => $pengeluaran->jumlah,
                'keterangan' => $pengeluaran->keterangan,
                'jenis' => 'pengeluaran',
                'tanggal' => $pengeluaran->bulan,
            ];
        }

        foreach ($transaksi_datas as $value) {
            if ($value['jenis'] == 'pemasukan') {
                $detail['pemasukan'] += $value['jumlah'];
            } else {
                $detail['pengeluaran'] += $value['jumlah'];
            }
        }

        if(count($transaksi_datas) == 0){
            $transaksi_datas[] = [
                'jumlah' => 0,
                'keterangan' => "-",
                'jenis' => '-',
                'tanggal' => '-',
            ];
            $detail = [
                'pemasukan' => 0,
                'pengeluaran' => 0,
                'sisa' => 0,
            ];
        }

        usort($transaksi_datas, function ($a, $b) {
            return strtotime($a['tanggal']) <=> strtotime($b['tanggal']);
        });

        $detail['sisa'] = $detail['pemasukan'] - $detail['pengeluaran'];

        return response()->json([$transaksi_datas, $detail], 200);
    }
}
