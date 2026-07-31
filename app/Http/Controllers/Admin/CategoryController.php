<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('teams')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }
}