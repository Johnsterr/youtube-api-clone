<?php

namespace App\Http\Controllers;

class CategoryController extends Controller
{
    public function index(): array
    {
        return [
            'All',
            'Trucks',
            'Tools'
        ];
    }
}
