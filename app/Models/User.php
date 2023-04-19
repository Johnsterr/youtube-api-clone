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

    public function scopeWithRelationships($query, array $with)
    {
        $relationships = ['channel'];

        return $query->with(array_intersect($with, $relationships));
    }

    public function scopeSearch($query, ?string $text)
    {
        return $query->where(function ($query) use ($text) {
            $query->where('name', 'like', "%$text%")
                ->orWhere('email', 'like', "%$text%");
        });
    }
}
