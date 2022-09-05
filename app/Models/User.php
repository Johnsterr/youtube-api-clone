<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    // Пример, вместо вызова метода "with" в контроллере
    //protected $with = ['channel'];

    public function channel()
    {
        return $this->hasOne(Channel::class);
    }
}
