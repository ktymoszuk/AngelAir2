<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telecamera extends Model
{
    use HasFactory;

    protected $table = "Telecamere";

    protected $fillable = ["Stato", "DataUpdate"];

    public $timestamps = false;
}
