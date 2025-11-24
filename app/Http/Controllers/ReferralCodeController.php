<?php

namespace App\Http\Controllers;

use App\Models\ReferralCode;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReferralCodeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $referralCodes = ReferralCode::select('*')->get();
            return DataTables::of($referralCodes)
                ->addIndexColumn()
                ->addColumn('action', function ($referralCode) {
                    return '<a href="' . route('code.edit', $referralCode->id) . '" class="text-black"><i class="text-2xl bx bx-edit"></i></a>
                            <form action="' . route('code.destroy', $referralCode->id) . '" method="POST" style="display: inline-block;">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="text-red-500"><i class="text-2xl bx bx-trash"></i></button>
                            </form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.code.index');
    }
    public function create()
    {
        return view('admin.code.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'referral_code' => 'required|string',

        ]);

        ReferralCode::create($request->all());

        return redirect()->route('code.index')
            ->with('success', 'Referral Code created successfully.');
    }

    public function edit($id)
    {
        $referralCode = ReferralCode::findOrFail($id);


        return view('admin.code.edit', compact('referralCode'));
    }

    public function update(Request $request, $id)
    {
        // Retrieve the ReferralCode model instance based on the provided ID
        $referralCode = ReferralCode::findOrFail($id);

        // Validate the request data
        $request->validate([
            'referral_code' => 'required|string',
        ]);

        // Update the referral code instance with validated data
        $referralCode->update([
            'referral_code' => $request->input('referral_code'),
        ]);

        // Redirect to the index route with a success message
        return redirect()->route('code.index')
            ->with('success', 'Referral Code updated successfully.');
    }
    public function destroy($id)
    {
        // Retrieve the ReferralCode model instance based on the provided ID
        $referralCode = ReferralCode::findOrFail($id);

        // Delete the referral code instance
        $referralCode->delete();

        // Redirect to the index route with a success message
        return redirect()->route('code.index')
            ->with('success', 'Referral Code deleted successfully.');
    }
}
