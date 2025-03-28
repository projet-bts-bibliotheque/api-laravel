<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomsReservation extends Model
{
    use HasFactory;

    public $timestamp = false;


    protected $table = 'rooms_reservations';

    protected $fillable = [
        'user_id',
        'room_id',
        'date'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'room_id' => 'integer',
        'date' => 'date'

    ];

    public function user()
    {
        return $this-belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongTo(Rooms::class);
    }
}
