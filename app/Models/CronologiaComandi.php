<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronologiaComandi extends Model
{
    use HasFactory;

    protected $table = "CronologiaComandi";

    protected $fillable = ["codComando", "codUtente", "isAutomatico", "isElaborato", "Deveui", "DataOra"];

    public $timestamps = false;

    
    public function comandi()
    {
        return $this->hasOne(Comando::class, 'id', 'codComando');
    }
}
