<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\HomeSlide;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class HomeSlideController extends Controller
{
    public function HomeSlider()
    {
        $homeslide = HomeSlide::find(1);

        return view('admin.home_slide.home_slide_all', compact('homeslide'));
    }

    public function UpdateSlider(Request $request)
    {
        $slide_id = $request->id;

        if ($request->file('home_slide'))
        {
            $image = $request->file('home_slide');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalName();

            Image::make($image)->resize(636,852)->save($save_url='upload/home_slide'.$name_gen);

            HomeSlide::findorFail($request->id)->update([
                'title' => $request->title,
                'video_url' => $request->video_url,
                'short_title' => $request->short_title,
                'home_slide' => $request->home_slide,
            ]);

            $notification = [
                'message' => 'Home Slide Updated Successfully',
                'alert_type' => 'success',
            ];

            return redirect()->back()->with($notification);
        }
        else
        {
            HomeSlide::findorFail($request->id)->update([
                'title' => $request->title,
                'video_url' => $request->video_url,
                'short_title' => $request->short_title,
            ]);

            $notification = [
                'message' => 'Home Slide Updated Successfully',
                'alert_type' => 'success',
            ];

            return redirect()->back()->with($notification);
        }
    }
}
