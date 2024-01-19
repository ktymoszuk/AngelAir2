<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "Reports";

    protected $fillable = [
        'Nome',
        'Frequenza',
        'Periodo',
        'isJSON',
        'isCSV',
        'isXML',
        'DataOra',
        'codStruttura',
    ];
}
