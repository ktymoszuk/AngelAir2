<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = "Logs";

    protected $fillable = ["Codice", "codUtente", "codStruttura", "codDispositivo", "Messaggio", "DataOra"];

    public $timestamps = false;

    public function utente()
    {
        return $this->hasOne(User::class, 'id', 'codUtente');
    }

}
