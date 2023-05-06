<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Пример, вместо вызова метода "with" в контроллере
    //protected $with = ['channel'];

    protected static $relationships = ['channel'];

    public function channel()
    {
        return $this->hasOne(Channel::class);
    }

    public function scopeWithRelationships($query, array|string $with)
    {
        return $query->with(array_intersect(Arr::wrap($with), static::$relationships));
    }

    public function scopeSearch($query, ?string $text)
    {
        return $query->where(function ($query) use ($text) {
            $query->where('name', 'like', "%$text%")
                ->orWhere('email', 'like', "%$text%");
        });
    }
}
