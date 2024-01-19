<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scenario extends Model
{
    use HasFactory;

    protected $table = "Scenari";

    protected $fillable = ["Nome", "Stato", "codAutomazione", "codRegolaAutoApertura", "codRegolaAutoChiusura"];

    public $timestamps = false;

    public function regoleautomazioni_apertura()
    {
        return $this->hasMany(RegoleAutomazioni::class, "Scenario", "codRegolaAutoApertura");
    }

    public function regoleautomazioni_chiusura()
    {
        return $this->hasMany(RegoleAutomazioni::class, "Scenario", "codRegolaAutoChiusura");
    }
}
