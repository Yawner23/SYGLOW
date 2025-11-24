<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BlogsController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $blogs = Blogs::query();

            return DataTables::of($blogs)
                ->addColumn('image', function ($blogs) {
                    return '<img src="' . asset($blogs->image) . '" alt="Banner Image" class="w-32">';
                })
                ->addColumn('action', function ($blogs) {
                    return '<a href="' . route('blogs.edit', $blogs->id) . '" class="text-black"><i class="text-4xl bx bx-edit"></i></a>
                        <form action="' . route('blogs.destroy', $blogs->id) . '" method="POST" style="display: inline-block;">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="text-red-500"><i class="text-4xl bx bx-trash"></i></button>
                        </form>
                       ';
                })
                ->addColumn('description', function ($blogs) {
                    // Remove brackets from the description
                    $cleanedDescription = str_replace(['<p>', '</p>'], '', $blogs->description);
                    return $cleanedDescription;
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        return view('admin.blogs.index'); // Ensure this view exists
    }
    // Show the form for creating a new banner
    public function create()
    {
        $category = Category::all();

        return view('admin.blogs.create', compact('category'));
    }

    // Store a newly created banner in storage
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'title' => 'nullable|string|max:255',
            'short_description' => 'nullable',
            'description' => 'nullable',
            'video_link' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'nullable|string',
        ]);

        $blogs = Blogs::create([
            'user_id' => $request->input('user_id'),
            'title' => $request->input('title'),
            'short_description' => $request->input('short_description'),
            'description' => $request->input('description'),
            'video_link' => $request->input('video_link'),
            'category_id' => $request->input('category_id'),
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/uploads'), $imageName);
            $blogs->update(['image' => 'images/uploads/' . $imageName]);
        }

        return redirect()->route('blogs.index');
    }

    // Show the form for editing the specified banner
    public function edit($id)
    {
        $blogs = Blogs::findOrFail($id);
        $category = Category::all();
        return view('admin.blogs.edit', compact('blogs', 'category'));
    }

    // Update the specified banner in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'nullable|string|max:255',
            'short_description' => 'nullable',
            'description' => 'nullable',
            'video_link' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|string',
        ]);

        $blogs = Blogs::findOrFail($id);

        // Prepare the updated data
        $updateData = [
            'user_id' => $request->input('user_id'),
            'title' => $request->input('title'),
            'short_description' => $request->input('short_description'),
            'description' => $request->input('description'),
            'video_link' => $request->input('video_link'),
            'category_id' => $request->input('category_id'),
        ];

        // Handle the file upload if a new file is provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/uploads'), $imageName);
            $updateData['image'] = 'images/uploads/' . $imageName;
        }

        // Update the banner record
        $blogs->update($updateData);

        return redirect()->route('blogs.index');
    }


    // Remove the specified banner from storage
    public function destroy($id)
    {
        $blogs = Blogs::findOrFail($id);
        $blogs->delete();

        return redirect()->route('blogs.index');
    }
}
