<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaAccounts extends Model
{
    use HasFactory;
    protected $table = 'social_media_accounts';
    protected $fillable = ['user_id', 'facebook', 'tiktok', 'instagram'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
