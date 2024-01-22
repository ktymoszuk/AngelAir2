<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class RawData extends Model
{
    protected $connection = 'mongodb';
    protected $collection = "RawData";
}
