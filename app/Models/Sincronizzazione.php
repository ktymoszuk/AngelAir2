<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sincronizzazione extends Model
{
    use HasFactory;

    protected $table = "Sincronizzazioni";

    protected $fillable= [
        'AxId',
        'AxAppTag',
        'AxSystemTag',
        'Operazione',
        'NomeTabella',
        'isSincCampo',
    ];
}