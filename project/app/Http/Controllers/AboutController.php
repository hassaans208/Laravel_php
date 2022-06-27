<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;
use App\Models\MultiImage;
use Image;

class AboutController extends Controller
{

    function AboutShow() 
    {
        return view('main.About.about');

    }
    function AboutSetting()
    {

        $about = About::find(1);

        return view('admin.about.about_edit', compact('about'));
    }

    function UpdateAbout(Request $request)
    {
        $id = $request->id;

        if ($request->file('image'))
        {
            $image = $request->file('image');

            $name_gen = hexdec(uniqid()). '.' . $image->getClientOriginalExtension();

            Image::make($image)->resize(632,852)->save('uploads/AboutImage/'. $name_gen);

            $image_path = 'uploads/AboutImage/' . $name_gen;

            About::findOrfail($id)->update([
                'main_title' => $request->main_title,
                'short_tick' => $request->short_tick,
                'short_desc' => $request->short_desc,
                'long_desc' => $request->long_desc,
                'image' => $request->name_gen,
            ]);

            $notification = array(
                'message' => 'About Updated with Image successfully', 
                'alert-type' => 'success'
            );
            
            return redirect()->back()->with('notification');
        }
        else 
        {
            About::findOrfail($id)->update([
                'main_title' => $request->main_title,
                'short_tick' => $request->short_tick,
                'short_desc' => $request->short_desc,
                'long_desc' => $request->long_desc,
            ]);
    
            $notification = array(
                'message' => 'About Updated without Image successfully', 
                'alert-type' => 'info'
            );
            
            return redirect()->back()->with('notification');
        }
    }
}
