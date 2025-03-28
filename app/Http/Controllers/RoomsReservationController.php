<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoomsReservationController extends Controller
{
    public function getReservation($userId, $roomId)
    {
        return RoomsReservation::orderBy('id', 'DESC')
            ->where('user_id',$userid)
            ->where('room_id',$roomId)
            ->first();
    }
}
