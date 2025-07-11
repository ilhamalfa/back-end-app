<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rumah extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function penghuni(){
        return $this->hasMany(Penghuni::class);
    }
}
