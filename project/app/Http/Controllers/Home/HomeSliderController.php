<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeSlider;
use Image;

class HomeSliderController extends Controller
{
    function HomeSlide()
    {
        $home = HomeSlider::find(1);
    
        return view('admin.HomeSlide.homeslide', compact('home'));

    }

    function UpdateSlider(Request $request)
    {
        $slide_id = $request->id;

        if ($request->file('image'))
        {
            $image = $request->file('image');

            $name_gen = hexdec(uniqid()). '.' . $image->getClientOriginalExtension();

            Image::make($image)->resize(632,852)->save('uploads/homeslide/'. $name_gen);

            $imgPath = 'uploads/homeslide/'. $name_gen;

            HomeSlider::findOrfail($slide_id)->update([
                'title' => $request->title,
                'short_tick' => $request->short_tick,
                'video' => $request->video,
                'image' => $name_gen,
            ]);

            $notification = array(
                'message' => 'Homeslide Updated with Image successfully', 
                'alert-type' => 'success'
            );
            
            return redirect()->back()->with('notification');
        } 
        else
        {
            HomeSlider::findOrfail($slide_id)->update([
                'title' => $request->title,
                'short_tick' => $request->short_tick,
                'video' => $request->video,
            ]);

            $notification = array(
                'message' => 'Homeslide Updated without Image successfully', 
                'alert-type' => 'success'
            );

            return redirect()->back()->with('notification');
        }
    }
}
