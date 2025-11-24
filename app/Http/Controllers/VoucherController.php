<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VoucherController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        if (request()->ajax()) {
            $vouchers = Voucher::query();
            return DataTables::of($vouchers)
                ->addColumn('actions', function ($voucher) {
                    $editUrl = route('voucher.edit', $voucher->id);
                    $deleteUrl = route('voucher.destroy', $voucher->id);
                    $csrfToken = csrf_token();
                    return "
                    <a href='{$editUrl}' class='text-black'><i class='text-4xl bx bx-edit'></i></a>

                    
                    <form action='{$deleteUrl}' method='POST' style='display:inline;'>
                        <input type='hidden' name='_token' value='{$csrfToken}'>
                        <input type='hidden' name='_method' value='DELETE'>
                        <button type='submit' class='text-red-500' onclick='return confirm(\"Are you sure you want to delete this voucher?\");'><i class='text-4xl bx bx-trash'></i></button>
                        
                    </form>
                ";
                })
                ->rawColumns(['actions']) // To allow HTML in the actions column
                ->make(true);
        }

        return view('admin.voucher.index');
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('admin.voucher.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|unique:vouchers|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric',
            'usage_limit' => 'nullable|integer',
            'status' => 'required|in:active,inactive,expired',
        ]);

        Voucher::create($validatedData);

        return redirect()->route('voucher.index')->with('success', 'Voucher created successfully.');
    }

    // Display the specified resource.
    public function show($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('vouchers.show', compact('voucher'));
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.voucher.edit', compact('voucher'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'code' => 'required|max:255|unique:vouchers,code,' . $id,
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric',
            'usage_limit' => 'nullable|integer',
            'status' => 'required|in:active,inactive,expired',
        ]);

        $voucher = Voucher::findOrFail($id);
        $voucher->update($validatedData);

        return redirect()->route('voucher.index')->with('success', 'Voucher updated successfully.');
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('voucher.index')->with('success', 'Voucher deleted successfully.');
    }
}
