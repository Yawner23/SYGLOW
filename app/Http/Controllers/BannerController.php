<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BannerController extends Controller
{
    // Show a list of banners
    public function index()
    {
        if (request()->ajax()) {
            $banners = Banner::query();

            return DataTables::of($banners)
                ->addColumn('banner_image', function ($banner) {
                    return '<img src="' . asset($banner->banner_image) . '" alt="Banner Image" class="w-32">';
                })
                ->addColumn('action', function ($banner) {
                    return '<a href="' . route('banners.edit', $banner->id) . '" class="text-black"><i class="text-4xl bx bx-edit"></i></a>
                            <form action="' . route('banners.destroy', $banner->id) . '" method="POST" style="display: inline-block;">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="text-red-500"><i class="text-4xl bx bx-trash"></i></button>
                            </form>
                           ';
                })
                ->rawColumns(['banner_image', 'action'])
                ->make(true);
        }

        return view('admin.banners.index'); // Ensure this view exists
    }
    // Show the form for creating a new banner
    public function create()
    {
        return view('admin.banners.create');
    }

    // Store a newly created banner in storage
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $banner = Banner::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        if ($request->hasFile('banner_image')) {
            $banner_image = $request->file('banner_image');
            $imageName = time() . '_' . $banner_image->getClientOriginalName();
            $banner_image->move(public_path('images/banner_image'), $imageName);
            $banner->update(['banner_image' => 'images/banner_image/' . $imageName]);
        }

        return redirect()->route('banners.index');
    }

    // Show the form for editing the specified banner
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    // Update the specified banner in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $banner = Banner::findOrFail($id);

        // Prepare the updated data
        $updateData = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ];

        // Handle the file upload if a new file is provided
        if ($request->hasFile('banner_image')) {
            $banner_image = $request->file('banner_image');
            $imageName = time() . '_' . $banner_image->getClientOriginalName();
            $banner_image->move(public_path('images/banner_image'), $imageName);
            $updateData['banner_image'] = 'images/banner_image/' . $imageName;
        }

        // Update the banner record
        $banner->update($updateData);

        return redirect()->route('banners.index');
    }


    // Remove the specified banner from storage
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return redirect()->route('banners.index');
    }
}
