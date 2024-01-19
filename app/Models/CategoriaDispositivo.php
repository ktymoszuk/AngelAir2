<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaDispositivo extends Model
{
    use HasFactory;

    protected $table = "TipoDisp";

    public $timestamps = false;
}
