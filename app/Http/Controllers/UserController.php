<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class UserController extends Controller
{
    public function index(): Collection
    {
        return User::all();
    }
}
