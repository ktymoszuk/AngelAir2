<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadReport extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "DownloadReport";

    protected $fillable = [
        'Nome',
        'codReport',
        'isDownload',
    ];
}
