<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog\Categories;
use App\Models\Subcategories;
use Illuminate\Support\Carbon;


class CategoriesController extends Controller
{
    function ViewCategories(){
        $data = Categories::latest()->get();
        return view('admin.blog.categories', compact('data'));
    }
    function ViewSubcategories(){
        $info = Categories::all();
        $data = Subcategories::latest()->get();
        return view('admin.blog.subcategories', compact('data','info'));
    }
    
    function AddCategories(Request $request){
        $request->validate([
            'category' => 'required'
        ], [
            'category.required' => 'Category is required'
        ]);

        Categories::insert([
            'categories' => $request->category,
            'created_at' => Carbon::now()
        ]);
        return redirect()->back();
    }


    function AddSubcategories(Request $request){
        $request->validate([
            'category' => 'required',
            'subcategory' => 'required'
        ], [
            'category.required' => 'Category is required',
            'subcategory.required' => 'Sub-category is required'
        ]);

        Subcategories::insert([
            'category_id' => $request->category,
            'subcategory' => $request->category,
            'created_at' => Carbon::now()
        ]);
        return redirect()->back();
    }
    
}