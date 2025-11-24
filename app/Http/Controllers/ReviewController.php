<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use App\Models\ReviewComment;
use Yajra\DataTables\DataTables;

class ReviewController extends Controller
{

    public function updateStatus(Request $request)
    {
        $rating = Rating::find($request->id);
        if ($rating) {
            $rating->status = $request->status;
            $rating->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Rating not found']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $ratings = Rating::with(['payment.customer', 'payment.deliveryAddress', 'reviewComment'])->select('*');

            return DataTables::of($ratings)
                ->addIndexColumn()
                ->addColumn('customer', function ($rating) {
                    return $rating->payment->customer->name;
                })
                ->addColumn('review_comment', function ($rating) {
                    return $rating->reviewComment->comment ?? 'N/A';
                })
                ->addColumn('image', function ($rating) {

                    // If no review comment or no image, return placeholder
                    if (!$rating->reviewComment || !$rating->reviewComment->image) {
                        return '<span class="text-gray-400 italic">No image</span>';
                    }

                    $imagePath = asset('images/uploads/' . $rating->reviewComment->image);

                    return '<img src="' . $imagePath . '" alt="Review Image" class="w-20 h-20 rounded object-cover">';
                })

                ->addColumn('status', function ($rating) {
                    $statusText = ucfirst(str_replace('_', ' ', $rating->status));
                    $checked = $rating->status === 'verified' ? 'checked' : '';
                    return '<input type="checkbox" class="status-checkbox" data-id="' . $rating->id . '" ' . $checked . '> ' . $statusText;
                })
                ->addColumn('rating', function ($rating) {
                    return $rating->rating;
                })
                ->rawColumns(['image', 'status'])
                ->make(true);
        }

        return view('admin.reviews.index');
    }



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // Adjust validation as needed
        ]);

        $rating = Rating::create([
            'payment_id' => $request->payment_id,
            'rating' => $validatedData['rating'],
        ]);

        if ($request->has('comment') || $request->hasFile('image')) {

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/uploads'), $imageName);
            $rating->update(['image' => 'images/uploads/' . $imageName]);

            ReviewComment::create([
                'rating_id' => $rating->id,
                'comment' => $validatedData['comment'],
                'image' => $imageName,
            ]);
        }

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }
}
