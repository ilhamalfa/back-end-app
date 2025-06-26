<?php

namespace App\Http\Controllers\Rumah;

use App\Http\Controllers\Controller;
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
}
