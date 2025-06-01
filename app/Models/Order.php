<?php

// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}

// app/Models/Event.php (если необходимо)

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}

