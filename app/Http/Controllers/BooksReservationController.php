<?php

namespace App\Http\Controllers;

use App\Models\BooksReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BooksReservationController extends Controller
{
    
    public function getReservation($userId, $bookId)
    {
        return BooksReservation::orderBy('id', 'DESC')
            ->where('user_id', $userId)
            ->where('book_id', $bookId)
            ->first();
    }

    public function getNotReturnedReservation($userId, $bookId)
    {
        return BooksReservation::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->whereNull('return_date')
            ->first();
    }

    public function getUserReservations($userId)
    {
        return BooksReservation::where('user_id', $userId)->get();
    }

    private function validate($request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,isbn',
        ]);

        if($validator->fails()) {
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
            return response()->json($validation, 400);
        }

        if (BooksReservation::where('book_id', $request->book_id)
            ->whereNull('return_date')
            ->exists()) {
            return response()->json(['error' => 'Book is already reserved and not returned'], 400);
        }

        $reservation = BooksReservation::create([
            'user_id' => Auth::user()->id,
            'book_id' => $request->book_id,
            'start' => NOW()
        ]);

        return response()->json($reservation, 201);
    }

    public function returnBook(Request $request)
    {
        $reservation = $this->getNotReturnedReservation(Auth::user()->id, $request->book_id);
        if($reservation === null) {
            return response()->json(['error' => 'Reservation not found or book returned.'], 404);
        }

        $validation = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,isbn'
        ]);

        if($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $reservation->update([
            'return_date' => NOW()
        ]);
        $reservation->save();

        return response()->json($reservation);
    }

    public function destroy($reservationId) {
        $reservation = BooksReservation::where('id', $reservationId)->get();
        if($reservation === null) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }

        $reservation->delete();
        return response()->json(['message' => 'Reservation deleted']);
    }

}
