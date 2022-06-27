<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\porfotlio;
use Illuminate\Support\Carbon;
use Image;

class PortfolioController extends Controller
{

    // main portfolio detail see
    
    function FrontViewDetail($id)
    {
        $data = porfotlio::find($id);
        return view('main.portfolio_details' , compact('data'));
    }



    // main portfolio see
    function FrontView()
    {
        $data = porfotlio::latest()->get();
        return view('main.portfolio_view', compact('data'));
    }

    // show portfolio

    function ShowPortfolio()
    {
        $data = porfotlio::all();
        return view('admin.portfolio.portfolio_veiw', compact('data'));
    }


    function InsertPortfolio()
    {
        return view('admin.portfolio.portfolio_insert');
    }


    function StoreInsertData(Request $request)
    {

        $request->validate([
            'main_title' => 'required',
            'main_line' => 'required',
            'sub_line' => 'required',
            'short_dec' => 'required',
            'long_dec' => 'required',
            'image' => 'required',
        ], [
            'main_title.required' => 'Title is required',
            'main_line.required' => 'Main Line is required',
            'sub_line.required' => 'Sub-Line is required',
            'short_dec.required' => 'Short Description is required',
            'long_dec.required' => 'Long Description is required',
            'image.required' => 'Image is required',
        ]);

        $image_get = $request->file('image');

        $name_gen = hexdec(uniqid()). '.' . $image_get->getClientOriginalExtension();

        Image::make($image_get)->resize(648,616)->save('uploads/Porfolio/'. $name_gen);

        $image_path = 'uploads/Porfolio/' . $name_gen;


        porfotlio::insert([
            'title' => $request->main_title,
            'main_title' => $request->main_line,
            'subline' => $request->sub_line,
            'short_dec' => $request->short_dec,
            'long_dec' => $request->long_dec,
            'image' => $image_path,
            'created_at' => Carbon::now(),
        ]);
        return view('admin.portfolio.portfolio_insert');
    }


    function PortfolioDelete($id) {

        $image = porfotlio::findOrfail($id);
        $del_img = $image->image;
        
        // Unlinking Images from DIRECTORY
        if(file_exists($del_img)){
            unlink($del_img);

        }


            porfotlio::findOrfail($id)->delete();
        

        return redirect()->back();
    }


    function PortfolioEdit($id){
        $data = porfotlio::find($id);
        return view('admin.portfolio.portfolio_update', compact('data'));
    }



    //update The Data
    function StoreUpdateData(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'main_title' => 'required',
            'main_line' => 'required',
            'sub_line' => 'required',
            'short_dec' => 'required',
            'long_dec' => 'required',
        ], [
            'id.required' => 'Fualty ID',
            'main_title.required' => 'Title is required',
            'main_line.required' => 'Main Line is required',
            'sub_line.required' => 'Sub-Line is required',
            'short_dec.required' => 'Short Description is required',
            'long_dec.required' => 'Long Description is required',
        ]);


        if ($request->file('image'))
        {
            $id = $request->id;
            $data = porfotlio::find($id);
        // Unlinking Images from DIRECTORY
        if(file_exists($data->image)){
            unlink($data->image);

        }
            
            
            $image = $request->file('image');

            $image_get = $request->file('image');

            $name_gen = hexdec(uniqid()). '.' . $image_get->getClientOriginalExtension();
    
            Image::make($image_get)->resize(648,616)->save('uploads/Porfolio/'. $name_gen);
    
            $image_path = 'uploads/Porfolio/' . $name_gen;

            porfotlio::findOrFail($id)->update([
                'title' => $request->main_title,
                'main_title' => $request->main_line,
                'subline' => $request->sub_line,
                'short_dec' => $request->short_dec,
                'long_dec' => $request->long_dec,
                'image' => $image_path,
                'created_at' => Carbon::now(),
            ]);
    
            return redirect()->back();

        } else {

            $id = $request->id;
            $data = porfotlio::find($id);


            porfotlio::findOrFail($id)->update([
                'title' => $request->main_title,
                'main_title' => $request->main_line,
                'subline' => $request->sub_line,
                'short_dec' => $request->short_dec,
                'long_dec' => $request->long_dec,
                'updated_at' => Carbon::now(),

            ]);


            return redirect()->back();
        }

        


    }


}
