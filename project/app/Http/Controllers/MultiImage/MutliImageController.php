<?php

namespace App\Http\Controllers\MultiImage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MultiImage;
use Illuminate\Support\Carbon;
use Image;

class MutliImageController extends Controller
{

    //Get new image Insertion form
    function ImageEdit()
    {
        return view('admin.about.multiimage_eit');
    }


    // Insert New Multiple Images 
    function ImageInsert(Request $request)
    {
        if ($request->file('multi_image')){
            $image = $request->file('multi_image');
    
            foreach ($image as $multi_imag) 
            {
    
                $name_gen = hexdec(uniqid()). '.' . $multi_imag->getClientOriginalExtension();
    
                Image::make($multi_imag)->resize(220,220)->save('uploads/MultiImage/'. $name_gen);
    
                $image_path = 'uploads/MultiImage/' . $name_gen;
    
                MultiImage::insert([
                    'multi_image' => $image_path,
                    'created_at' => Carbon::now(),
                ]);
    
            }
                $notification = array(
                    'message' => 'About Updated with Image successfully', 
                    'alert-type' => 'success'
                );
                
                return redirect()->back()->with('notification');
            }
            else
            {
                $notification = array(
                    'message' => 'Nothing Updated', 
                    'alert-type' => 'warning'
                );
                
                return view('admin.about.multiimage_eit')->with('notification');

            }
            
        }

        // Find Image to show in table
        function ImageFind() {
            return view('admin.about.show_edit');
        }


        // get Image to Delete

        function ImgGetDelete($id) {

            $image = MultiImage::findOrfail($id);
            $del_img = $image->multi_image;
            
            // Unlinking Images from DIRECTORY
            if(file_exists($del_img)){
                unlink($del_img);

            }


            MultiImage::findOrfail($id)->delete();
            

            return redirect()->back();
        }

        // Get Image to load on edit form

        function ImgGetedit($id) {

            $data = MultiImage::find($id);

            return view('admin.about.single_image_edit', compact('data'));
        }


        //Update Image

        function ImageUpdate(Request $request) {

            $id_img = $request->id;
            $image = MultiImage::findOrfail($id_img);
            $del_img = $image->multi_image;

            // Unlinking Images from DIRECTORY
            if (file_exists($del_img)){
                unlink($del_img);
            }

            $image_get = $request->file('multi_image');
    


    
            $name_gen = hexdec(uniqid()). '.' . $image_get->getClientOriginalExtension();

            Image::make($image_get)->resize(220,220)->save('uploads/MultiImage/'. $name_gen);

            $image_path = 'uploads/MultiImage/' . $name_gen;

            MultiImage::findOrfail($id_img)->update([
                'multi_image' => $image_path,
                'updated_at' => Carbon::now(),
            ]);          

            return view('admin.about.show_edit');
        }
        
    
}