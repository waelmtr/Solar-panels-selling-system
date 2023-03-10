<?php

namespace Database\Seeders;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'email'=> 'admin@gmail.com' , 
            'password'=> bcrypt('12345678') , 
            'role_id'=> 1 ,
        ]) ;
    }
}
