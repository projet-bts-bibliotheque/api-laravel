<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoomsReservationController extends Controller
{
    public fucntion getReservation($userId, $roomId)
    {
        return RoomsReservation::where('user_id',$userid)
            ->where('room_id',$roomId)
            ->first();
    }
}
