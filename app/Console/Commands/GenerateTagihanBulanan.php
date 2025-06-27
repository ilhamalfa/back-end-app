<?php

namespace App\Console\Commands;

use App\Models\Penghuni;
use App\Models\Tagihan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateTagihanBulanan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagihan:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $penghuniDatas = Penghuni::all();
        $bulanIni = Carbon::now()->format('Y-m') . '-01';
        
        foreach ($penghuniDatas as $penghuni) {
            $tagihan = Tagihan::where('penghuni_id', $penghuni->id)
                                ->where('is_aktif', true)
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
        }

        return "Tagihan bulan $bulanIni berhasil dibuat.";
    }
}
