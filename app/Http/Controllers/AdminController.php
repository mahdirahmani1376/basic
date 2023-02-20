<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = [
            'message' => 'admin profile updated successfully',
            'alert_type' => 'success',
        ];

        return redirect('/login')->with($notification);
    }

    public function profile()
    {
        $adminData = \auth()->user();
        return Response::view('admin.admin_profile_view',compact('adminData'));
    }

    public function editProfile()
    {
        $editData = \auth()->user();
        return Response::view('admin.admin_profile_edit',compact('editData'));
    }

    public function StoreProfile(Request $request)
    {
        $data = \auth()->user();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = $request->password;

        if ($request->file('profile_image')){
            $file = $request->file('profile_image');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'),$filename);
            $data->profile_image = $filename;
        }

        $data->save();

        $notification = [
            'message' => 'admin profile updated successfully',
            'alert_type' => 'success',
        ];

        return redirect(route('admin.profile'))->with($notification);
    }

    public function changePassword()
    {
        return view('admin.admin_change_password');
    }

    public function updatePassword(Request $request)
    {
        $validateData = $request->validate([
           'oldpassword' => 'required',
            'newpassword' => 'required',
            'confirm_password' => 'required|same:newpassword',
        ]);

        $user = \auth()->user();
        $hashedPassword = $user->password;

        if(Hash::check($validateData['oldpassword'],$hashedPassword)){
            $user->password = bcrypt($validateData['newpassword']);
            $user-> save();

            session()->flash('success','password Updated Successfully');
            return redirect()->back();
        }else {
            session()->flash('success', 'password Updated Successfully');
            return redirect()->back();
        }
    }
}
