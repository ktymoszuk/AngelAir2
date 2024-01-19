<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Soglia extends Model
{
    use HasFactory;

    protected $table = "Soglie";

    protected $fillable = ["Nome", "TipoDatoSoglia", "TipoSoglia", "OperatoreMinimo", "ValoreMinimo", "AliasMinimo", "ColoreMinimo", "ColonnaAssociata", "ValoreAssociato", "Descrizione", "codAutomazione", "codCategoriaSoglia",
    "codTipoDisp"];

    public $timestamps = false;

    public function categoriasoglia()
    {
        return $this->hasOne(CategoriaSoglia::class, 'id', 'codCategoriaSoglia');
    }

    public function categoriadispositivo()
    {
        return $this->hasOne(CategoriaDispositivo::class, 'IdTipo', 'codTipoDisp');
    }

    public function automazione()
    {
        return $this->hasOne(Automazione::class, 'id', 'codAutomazione');
    }

    public function sogliadispositivi()
    {
        return $this->belongsToMany(Dispositivo::class, SogliaDispositivo::class, 'codSoglia', 'codDispositivo');
    }

    
    public function notifica()
    {
        return $this->belongsTo(Notifica::class, 'id', 'codSoglia')->where("codUtente", Auth::id());
    }
}