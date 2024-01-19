<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegoleAutomazioni extends Model
{
    use HasFactory;

    protected $table = "RegoleAutomazioni";

    protected $fillable = ["Nome", "codSoglia", 
    "codComando", "codDispositivo",
    "codCategoriaDisp", "codCategoriaAutomazione", "codAutomazione", "Appartenenza"];
}
