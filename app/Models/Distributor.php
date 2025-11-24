<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'distributor_type',
        'region',
        'province',
        'city',
        'brgy',
        'name',
        'code',
        'valid_id_path',
        'selfie_with_id_path',
        'photo_with_background_path',
        'contact_number',
        'profile_picture'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
