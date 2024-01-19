<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Dispositivo extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'Dispositivi';

    protected $fillable = [
        'AxId',
        'AxAppTag',
        'AxSystemTag',
        'Nome',
        'DevEui',
        'Descrizione',
        'id_1liv',
        'id_2liv',
        'id_3liv',
        'id_4liv',
        'Latitudine',
        'Longitudine',
        "Km",
        'isAllarme',
        'isAbilitato',
        'isSincronizzato',
        'SerialNumber',
        'AppID',
        'JoinType',
        'AppEui',
        'AppKey',
        'Class',
        'DevAddr',
        'NwkSessionKey',
        'AppSessionKey',
        "IdTratta",
        "IdCarreggiata",
        "IdSvincolo",
        "IdRamo",
        "IdRotatoria",
        "codTipoDisp",
        "codStruttura",
        'AppTag',
        "CodiceStazione",
    ];

    public $timestamps = false;

    // RELAZIONI

    public function struttura()
    {
        return $this->hasOne(Struttura::class, 'id', 'codStruttura');
    }

    public function tipodispositivo()
    {
        return $this->hasOne(TipoDispositivo::class, 'IdTipo', 'codTipoDisp');
    }

    public function comandodispositivo()
    {
        return $this->belongsToMany(Comando::class, ComandoDispositivo::class, 'codDispositivo', 'codComando');
    }

    public function telecamere()
    {
        return $this->hasMany(Telecamera::class, 'codDispositivo', 'id');
    }

    public function sogliadispositivo()
    {
        return $this->belongsToMany(Soglia::class, SogliaDispositivo::class, 'codDispositivo', 'codSoglia');
    }

}
