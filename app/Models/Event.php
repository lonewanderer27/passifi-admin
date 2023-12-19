<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'avatar',
        'description',
        'date',
        'time',
        'location',
        'organizer',
        'organizer_email',
        'organizer_approval',
        'user_id',
        'invite_code'
    ];

    // Relationship to user
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship to Attendee
    public function attendees() {
        return $this->hasMany(Attendee::class, 'event_id');
    }

    // Relationship to Guests
    public function guests() {
        return $this->hasMany(Guest::class, 'event_id');
    }
}
