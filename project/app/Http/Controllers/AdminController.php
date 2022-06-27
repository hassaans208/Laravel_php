<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();


        $notification = array(
            'message' => 'User logged out successfully', 
            'alert-type' => 'success'
        );


        return redirect('/login')->with($notification);
    }

    public function Profile()
    {
        $id     = Auth::user()->id;
        $info   = User::find($id);
        return view('admin.admin_profile', compact('info'));
    }

    public function EditProfile()
    {
        $id     = Auth::user()->id;
        $info   = User::find($id);
        return view('admin.edit_admin_profile', compact('info'));
    }
    public function UpdateProfile(Request $request)
    {
        $id                 = Auth::user()->id;
        $edinfo             = User::find($id);

        $edinfo->name       = $request->name;
        $edinfo->email      = $request->email;
        $edinfo->username   = $request->username;

        if ($request->file('profile_image'))
        {
            $file                       = $request->file('profile_image');

            $filename                   = date('YmdHi'). $file->getClientOriginalName();
            $file                       -> move(('uploads/adminImage'),$filename);
            $edinfo['profile_image']    = $filename;
        }

        $edinfo->save();


        $notification = array(
            'message' => 'Admin Profile Updated Successfully', 
            'alert-type' => 'info'
        );

        return redirect()->route('admin.profile')->with($notification);    
    }

    public function ChangePassword()
    {
        return view('admin.change_password');
    }


    public function UpdatePassword(Request $request)
    {
        $validate_data = $request->validate([
            'Old_Password'      => 'required',
            'New_Password'      => 'required',
            'Confirm_Password'  => 'required|same:New_Password',
        ]);

        $hashedPass  = Auth::user()->password;
        if(Hash::check($request->Old_Password, $hashedPass)) 
        {
            $users = User::find(Auth::id());
            $users->password = bcrypt($request->New_Password);
            $users->save();

            session()->flash('message', 'Password Changes Successfully');
            return redirect()->back();
        } 
        else 
        {
            session()->flash('message', 'Old Password Does Not Match');
            return redirect()->back();
        }
    }

}
