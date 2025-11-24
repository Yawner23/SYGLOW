<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{

    public function index()
    {
        if (request()->ajax()) {
            $category = Category::query();

            return DataTables::of($category)
                ->addColumn('action', function ($category) {
                    return '<a href="' . route('categories.edit', $category->id) . '" class="text-black"><i class="text-4xl bx bx-edit"></i></a>
                            <form action="' . route('categories.destroy', $category->id) . '" method="POST" style="display: inline-block;">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="text-red-500"><i class="text-4xl bx bx-trash"></i></button>
                            </form>
                           ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.categories.index'); // Ensure this view exists
    }
    // Show the form for creating a new banner
    public function create()
    {
        return view('admin.categories.create');
    }

    // Store a newly created banner in storage
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'category_name' => $request->input('category_name')
        ]);

        return redirect()->route('categories.index', compact('category'));
    }

    // Show the form for editing the specified banner
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    // Update the specified banner in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);

        // Prepare the updated data
        $updateData = [
            'category_name' => $request->input('category_name'),
        ];


        // Update the banner record
        $category->update($updateData);

        return redirect()->route('categories.index', compact('category'));
    }

    // Remove the specified banner from storage
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index');
    }
}
