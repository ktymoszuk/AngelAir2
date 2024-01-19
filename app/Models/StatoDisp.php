<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class StatoDisp extends Model
{
    use HasFactory;

    // protected $connection = "mysql2";
    protected $connection = "mongodb";

    protected $collection = "StatoDisp";
}
