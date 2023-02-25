<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Category extends Eloquent
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'categories';
    protected $casts = [
        'features'=> 'array' , 
        'products'=> 'array' ,
    ] ; 
    protected $fillable = [
     'name' ,
     'features' ,
     'products' ,
    ] ;
}
