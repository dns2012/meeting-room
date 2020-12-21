<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'room',
    ];

    /**
     * Booking one to one get room.
     *
     * @return object
     */
    public function room()
    {
        return $this->hasOne('App\Room', 'id', 'room_id');
    }
}
