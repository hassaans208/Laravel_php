<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blogging;
use App\Models\Blog\Categories;
use App\Models\Subcategories;
use Illuminate\Support\Carbon;
use Image;
// use Illuminate\Database\Eloquent\Builder::paginator();
class BlogsController extends Controller
{
    function ViewBlogs(){
        $data = Blogging::latest()->get();
        return view('admin.Blog.blog_view', compact('data'));
    }
    function FrontBlog(){
        $data = Blogging::latest()->paginate(3);
        $info = Subcategories::all();
        $sub_cats = Categories::all();
        return view('main.blog.blog_all', compact('data'));
    }
    function IndivBlog($id){
        $data = Blogging::find($id);
        return view('main.blog.blog_single', compact('data'));
    }
    function AddBlog(){
        $data = Categories::all();
        $info = Subcategories::all();
        return view('admin.Blog.blog_insert', compact('info','data'));

    }

        //update The Data
        function InsertBlog(Request $request)
        {
            
            
            if ($request->file('image'))
            {
                // $request->validate([
                //     'title'     => 'required',
                //     'category'  => 'required',
                //     'subcategory' => 'required',
                //     'subline'   => 'required',
                //     'short_dec' => 'required',
                //     'long_dec'  => 'required',
                //     'image'     => 'required'
                // ], [
                //     'id.required' => 'Fualty ID',
                //     'title.required' => 'Title is required',
                //     'category.required' => 'Main Line is required',
                //     'subcategory.required' => 'Sub-Line is required',
                //     'short_dec.required' => 'Short Description is required',
                //     'long_dec.required' => 'Long Description is required',
                //     'image.required' => 'Image is required',
                // ]);
                
                
                $image = $request->file('image');
    
                $image_get = $request->file('image');
    
                $name_gen = hexdec(uniqid()). '.' . $image_get->getClientOriginalExtension();
        
                Image::make($image_get)->resize(648,616)->save('uploads/blogs/'. $name_gen);
        
                $image_path = 'uploads/blogs/' . $name_gen;
    
                Blogging::insert([
                    'title' => $request->title,
                    'subline' => $request->subline,
                    'cat_id' => $request->category,
                    'tags'      => $request->tags,
                    'subcat_id' => $request->subcategory,
                    'summary_text' => $request->short_dec,
                    'long_desc' => $request->long_dec,
                    'images' => $image_path,
                    'created_at' => Carbon::now(),
                ]);
        
                return redirect()->back();
    
            } else {
                // $request->validate([
                //     'title'     => 'required',
                //     'category'  => 'required',
                //     'tags'  => 'required',
                //     'subcategory' => 'required',
                //     'subline'   => 'required',
                //     'short_dec' => 'required',
                //     'long_dec'  => 'required',
                // ], [
                //     'title.required' => 'Title is required',
                //     'tags.required' => 'Tags is required',
                //     'category.required' => 'Category is required',
                //     'subcategory.required' => 'Sub-Category is required',
                //     'short_dec.required' => 'Short Description is required',
                //     'long_dec.required' => 'Long Description is required',
                // ]);

    
                Blogging::insert([
                    'title' => $request->title,
                    'subline' => $request->subline,
                    'tags'      => $request->tags,
                    'cat_id' => $request->category,
                    'subcat_id' => $request->subcategory,
                    'summary_text' => $request->short_dec,
                    'long_desc' => $request->long_dec,
                    'created_at' => Carbon::now(),
                ]);
    
    
                return redirect()->back();
            }
    
    }    
}
