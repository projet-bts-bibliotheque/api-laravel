<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomsReservation;
use App\Models\BooksReservation;
use Illuminate\Support\Facades\Validator;

class RoomsReservationController extends Controller
{
    public function getReservation($userId, $roomId)
    {
        return RoomsReservation::orderBy('id', 'DESC')
            ->where('user_id',$userid)
            ->where('room_id',$roomId)
            ->first();
    }

    public function getUserReservations($userId)
    {
        return RoomsReservation::where('user_id', $userId)->get();
    }

    private function validate($request){
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date'
        ]);

        if($validator->fails()){
            return $validator->errors();
        }

        return true;
    }

    public function index()
    {
        $reservations = BooksReservation::all();
        return response()->json($reservations);
    }

    public function showReservations() {
        $reservations = $this->getUserReservations(Auth::user()->id);
        return response()->json($reservations);
    }

    public function showUserReservations($userId) {
        $reservations = $this->getUserReservations($userId);
        return response()->json($reservations);
    }

    public function store(Request $request)
    {
        $validation = $this->validate($request);
        if($validation !== true) {
            return response()->json($validation,400);
        }

        if(RoomsReservation::where('room_id', $request->room_id)
            ->Where('date')
            ->exists()) {
            return response()->json(['error' => 'Room is already reserved'], 400);
            }
        
        $reservation = RoomsReservation::create([
            'user_id' => Auth::user()->id,
            'room_id' => $request->room_id,
            'date' => $request->date,
        ]);

        return response()->json($reservation, 201);
    }

    public function destroy($reservationId) {
        $reservation = RoomsReservation::where('id',$reservationId)->get();
        if($reservation === null) {
            return response()->json(['error' => 'Reservation not found'], 404);

        }

        $reservation->delete();
        return reponse()->json(['message' => 'Reseravation deleted']);
    }
}
