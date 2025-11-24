<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewComment extends Model
{
    use HasFactory;
    protected $table = 'review_comments';

    // Specify the fields that can be mass-assigned
    protected $fillable = [
        'rating_id',
        'comment',
        'image',
    ];

    // Define the relationship with the Rating model
    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }
}
