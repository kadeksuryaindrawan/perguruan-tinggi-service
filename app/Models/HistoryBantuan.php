<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryBantuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_desa',
        'id_permasalahan',
        'user_id',
        'desa',
        'potensi',
        'permasalahan',
        'bantuan',
        'perguruan_tinggi'
    ];
}
