<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return view('categories.index', ["categories" => $categories]);
    }


    public function create()
    {
        return view("categories.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        toastr()->success('Category created successfully.');
        return redirect()->route('categories.index');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.update', ['category' => $category]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:categories,name,' . $id,
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        toastr()->success('Category updated successfully.');
        return redirect()->route('categories.index');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if ($category) {
            toastr()->success('Category deleted successfully.');
            Category::destroy($id);
            return redirect()->route('categories.index');
        } else {
            toastr()->error('Category not deleted');
        }
    }
}
