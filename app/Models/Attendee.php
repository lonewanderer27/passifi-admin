<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'guest_id',
    ];

    // Relationship to User
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship to Event
    public function event() {
        return $this->belongsTo(Event::class, "event_id");
    }

    // Relationshop to Guest
    public function guest() {
        return $this->belongsTo(Guest::class, "guest_id");
    }
}
