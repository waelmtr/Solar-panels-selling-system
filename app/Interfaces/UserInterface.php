<?php

namespace App\Interfaces;

interface UserInterface{
    public function register($request);
    public function login($request);
    public function logout();
}