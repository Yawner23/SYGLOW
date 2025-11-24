<?php

namespace App\Http\Controllers;

use App\Models\Partners;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PartnersController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $partners = Partners::query();

            return DataTables::of($partners)
                ->addColumn('partners_image', function ($partners) {
                    return '<img src="' . asset($partners->partners_image) . '" alt="Partner Image" class="w-32">';
                })
                ->addColumn('action', function ($partners) {
                    return '<a href="' . route('partners.edit', $partners->id) . '" class="text-black"><i class="text-4xl bx bx-edit"></i></a>
                            <form action="' . route('partners.destroy', $partners->id) . '" method="POST" style="display: inline-block;">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="text-red-500"><i class="text-4xl bx bx-trash"></i></button>
                            </form>
                           ';
                })
                ->rawColumns(['partners_image', 'action'])
                ->make(true);
        }

        return view('admin.partners.index'); // Ensure this view exists
    }

    // Show the form for creating a new banner
    public function create()
    {
        return view('admin.partners.create');
    }

    // Store a newly created banner in storage
    public function store(Request $request)
    {
        $request->validate([
            'partners_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $partners = Partners::create();

        if ($request->hasFile('partners_image')) {
            $partners_image = $request->file('partners_image');
            $imageName = time() . '_' . $partners_image->getClientOriginalName();
            $partners_image->move(public_path('images/partners_image'), $imageName);
            $partners->update(['partners_image' => 'images/partners_image/' . $imageName]);
        }

        return redirect()->route('partners.index');
    }

    // Show the form for editing the specified banner
    public function edit($id)
    {
        $partners = Partners::findOrFail($id);
        return view('admin.partners.edit', compact('partners'));
    }

    // Update the specified banner in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'partners_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $partners = Partners::findOrFail($id);

        // Prepare the updated data
        $updateData = [];

        // Handle the file upload if a new file is provided
        if ($request->hasFile('partners_image')) {
            $partners_image = $request->file('partners_image');
            $imageName = time() . '_' . $partners_image->getClientOriginalName();
            $partners_image->move(public_path('images/partners_image'), $imageName);
            $updateData['partners_image'] = 'images/partners_image/' . $imageName;
        }

        // Update the banner record
        $partners->update($updateData);

        return redirect()->route('partners.index');
    }


    // Remove the specified banner from storage
    public function destroy($id)
    {
        $partners = Partners::findOrFail($id);
        $partners->delete();

        return redirect()->route('partners.index');
    }
}
