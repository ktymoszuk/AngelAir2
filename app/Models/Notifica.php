<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifica extends Model
{
    use HasFactory;

    protected $table = "Notifiche";

    protected $fillable = ["codSoglia", "codUtente", "isNotifica"];

    public $timestamps = false;
}
