<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
        // Validate form inputs
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'comment' => 'required|string|max:1000',
            'g-recaptcha-response' => ['required', function ($attribute, $value, $fail) use ($request) {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => env('RECAPTCHA_SECRET_KEY'), // Add your secret key in .env
                    'response' => $value,
                ])->json();

                if (!($response['success'] ?? false)) {
                    Log::warning('Contact Us captcha failed', [
                        'email' => $request->email,
                        'ip' => $request->ip(),
                        'response' => $response,
                    ]);
                    $fail('Captcha verification failed.');
                } else {
                    Log::info('Contact Us captcha passed', [
                        'email' => $request->email,
                        'ip' => $request->ip(),
                    ]);
                }
            }],
        ]);

        // Create the contact record
        ContactUs::create($validatedData);

        Log::info('Contact Us form submitted', [
            'email' => $request->email,
            'name' => $request->name,
            'ip' => $request->ip(),
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Your message has been submitted successfully!');
    }
}
