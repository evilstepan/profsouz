<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'date_time',
        'location',
        'responsible_person',
        'participants',
        'image_path',
        'description',
        'status',
        'max_participants'
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    // Define the route key name for route model binding
    public function getRouteKeyName()
    {
        return 'id';
    }
    public function acceptedEvents()
    {
        return $this->hasMany(AcceptedEvent::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'accepted_events');
    }

    // Связь с пользователем (опционально)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eventParticipations()
    {
        return $this->hasMany(EventParticipation::class);
    }

    public function getCurrentParticipantsCount()
    {
        return $this->acceptedEvents()->count() + $this->eventParticipations()->where('status', 'accepted')->count();
    }

    public function hasAvailableSpots()
    {
        // Если max_participants равно null или 0, значит количество участников не ограничено
        if (!$this->max_participants) {
            return true;
        }

        return $this->getCurrentParticipantsCount() < $this->max_participants;
    }

    public function getAvailableSpotsCount()
    {
        if (!$this->max_participants) {
            return '∞'; // Бесконечность
        }

        return $this->max_participants - $this->getCurrentParticipantsCount();
    }
}

