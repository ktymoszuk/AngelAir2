<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnaDisp extends Model
{
    use HasFactory;

    protected $table = "AnaDisp";

    protected $fillable = ["Nome", "DevEui", "idTipologia", "AppID", "JoinType", "AppEui", "AppKey", 
    "Class", "DevAddr", "NwkSessionKey", "AppSessionKey", "AppTag"];

    public $timestamps = false;
}
