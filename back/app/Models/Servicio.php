<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;
    protected $fillable = [
        'item',
        'subitem',
        'nombre',
        'estado',
        'imagen',
        'monto',
        'unit_id',
        ];

    public function unit(){
        return $this->belongsTo(Unit::class);
    }
}
