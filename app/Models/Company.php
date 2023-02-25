<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Company extends Authenticatable
{
    use HasApiTokens , HasFactory, Notifiable;
    protected $connection = 'mongodb';
    protected $collection = 'companies';
    protected $casts = [
        'products'=>'array' ,
        'feedbacks'=>'array' ,
        'Appointments'=>'array' ,
    ];
    protected $fillable = [
       'name' ,
       'logo' ,
       'location' ,
       'rate' ,
       'email' ,
       'password' ,
       'phone' ,
       'products' , 
       'feedbacks' ,
      'Appointments' ,
       'role_id' ,
    ];

    protected $hidden = [
        'password'
    ];
}
