<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ContactUsController extends Controller
{

    public function index(Request $request)
    {
        if (request()->ajax()) {
            $contact_us = ContactUs::query();

            return DataTables::of($contact_us)

                ->addColumn('action', function ($contact) {
                    return '
                            <form action="' . route('contact_us.destroy', $contact->id) . '" method="POST" style="display: inline-block;">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="text-red-500"><i class="text-4xl bx bx-trash"></i></button>
                            </form>
                           ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.contact_us.index');
    }


    public function destroy($id)
    {
        $contact = ContactUs::findOrFail($id);
        $contact->delete();
        return redirect()->route('contact_us.index')->with('success', 'Your message has been deleted successfully!');
    }

    public function store_contact_us(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'comment' => 'required|string|max:1000',
        ]);

        // Create the contact record using the validated data
        ContactUs::create($validatedData);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Your message has been submitted successfully!');
    }
}
