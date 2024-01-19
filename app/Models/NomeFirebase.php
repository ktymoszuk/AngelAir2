<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NomeFirebase extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = "NomiFirebase";

    
    public function tipodisp()
    {
        return $this->hasOne(TipoDispositivo::class, 'IdTipo', 'NomeBart');
    }
}
