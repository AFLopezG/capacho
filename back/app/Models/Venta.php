<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    protected $fillable = [
    'fecha',
    'numero',
    'servicio_id',
    'user_id',
    ];

    public function servicio(){
        return $this->belongsTo(Servicio::class);
    }
}
