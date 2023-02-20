<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AskForm extends Model
{
    use HasFactory;

    protected $table = "ask_form";
    protected $fillable = [
        'ask_one',
        'ask_two',
        'ask_three',
        'ask_four',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
