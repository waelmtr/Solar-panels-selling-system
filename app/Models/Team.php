<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Team extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $connection = 'mongodb';
    protected $collection = 'teams';
    protected $fillable = [
        'location' ,
        'name' ,
        'available' ,
        'email' ,
        'password' ,
        'phone' ,
        'role_id',
        'company_id' ,
    ] ;


}
