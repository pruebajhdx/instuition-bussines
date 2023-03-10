<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $table = 'responses_user';
    protected $fillable = [
        'favorite_food',
        'favorite_artist',
        'favorite_place',
        'favorite_color',
        'desc_ask_one',
        'desc_ask_two',
        'desc_ask_three',
        'desc_ask_four',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}