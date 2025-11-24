<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductTypeController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $product_type = ProductType::with('category')->select('*');

            return DataTables::of($product_type)
                ->addColumn('edit_url', function ($product_type) {
                    return route('product_type.edit', $product_type->id); // Uses project id
                })
                ->addColumn('delete_url', function ($product_type) {
                    return route('product_type.destroy', $product_type->id); // Uses project id
                })
                ->addColumn('action', function ($product_type) {
                    return '<a href="' . route('product_type.edit', $product_type->id) . '" class="text-black"><i class="text-4xl bx bx-edit"></i></a>
                            <form action="' . route('product_type.destroy', $product_type->id) . '" method="POST" style="display: inline-block;">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="text-red-500"><i class="text-4xl bx bx-trash"></i></button>
                            </form>
                           ';
                })
                ->make(true);
        }
        return view('admin.product_type.index'); // Ensure this view exists
    }
    // Show the form for creating a new banner
    public function create()
    {
        $category = Category::all();

        return view('admin.product_type.create', compact('category'));
    }

    // Store a newly created banner in storage
    public function store(Request $request)
    {
        $category = Category::all();

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_type' => 'required|string|max:255',
        ]);

        $product_type = ProductType::create([
            'category_id' => $request->input('category_id'),
            'product_type' => $request->input('product_type')
        ]);

        return redirect()->route('product_type.index', compact('category'));
    }

    // Show the form for editing the specified banner
    public function edit($id)
    {
        $product_type = ProductType::findOrFail($id);
        $category = Category::all();

        return view('admin.product_type.edit', compact('product_type', 'category'));
    }

    // Update the specified banner in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_type' => 'required|string|max:255',
        ]);
        $product_type = ProductType::findOrFail($id);
        // Prepare the updated data
        $updateData = [
            'category_id' => $request->input('category_id'),
            'product_type' => $request->input('product_type'),
        ];
        // Update the banner record
        $product_type->update($updateData);

        return redirect()->route('product_type.index');
    }

    // Remove the specified banner from storage
    public function destroy($id)
    {
        $product_type = ProductType::findOrFail($id);
        $product_type->delete();

        return redirect()->route('product_type.index');
    }
}
