<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class TeacherController extends Controller
{
    public function index(): Collection
    {
        return Teacher::all();
    }
}
