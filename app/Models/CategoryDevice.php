<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class CategoryDevice extends Eloquent
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'category_devices';
    protected $casts = [
        'devices' => 'array'
    ];
    protected $fillable = [
       'name' ,
       'devices' ,
    ];
}
