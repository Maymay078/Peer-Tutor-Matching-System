<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tutor_id',
        'expertise',
        'payment_details',
        'availability',
    ];

    protected $casts = [
        'availability' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookingSessions()
    {
        return $this->hasMany(BookingSession::class);
    }

    public function feedbacks()
    {
        return $this->hasManyThrough(
            Feedback::class,
            User::class,
            'id', // Foreign key on users table...
            'to_user_id', // Foreign key on feedbacks table...
            'user_id', // Local key on tutors table...
            'id' // Local key on users table...
        );
    }
}
