<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SogliaDispositivo extends Model
{
    use HasFactory;

    protected $table = "SoglieDispositivi";

    protected $fillable = ["codDispositivo", "codSoglia"];

    public $timestamps = false;

    public function soglie()
    {
        return $this->belongsToMany(Soglia::class, 'codSoglia', 'id');
    }

    public function dispositivi()
    {
        return $this->hasMany(Dispositivo::class, 'id', 'codDispositivo');
    }

}