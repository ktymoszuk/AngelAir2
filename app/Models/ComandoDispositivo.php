<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComandoDispositivo extends Model
{
    use HasFactory;

    protected $table = "ComandiDispositivi";

    protected $fillable = ["codComando", "codDispositivo"];

    public $timestamps = false;

    public function dispositivi()
    {
        return $this->hasMany(Dispositivo::class, 'id', 'codDispositivo');
    }
}
