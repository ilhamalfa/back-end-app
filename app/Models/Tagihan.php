<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    public function penghuni(){
        return $this->belongsTo(Penghuni::class);
    }

    public function pembayaran(){
        return $this->hasOne(tagihan::class);
    }

}

