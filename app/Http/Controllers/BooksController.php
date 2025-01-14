<?php

namespace App\Http\Controllers;

use App\Models\Books;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BooksController extends Controller
{
    // Récupérer la liste de tous les livres.
    public function index() {
        $books = Books::all();
        return response()->json($books);
    }

    // Récupérer le livre avec l'ID passé en paramètre.
    public function show($id) {
        $book = Books::where('isbn', '=', $id)->firstOr(function () {
            return response()->json([
                'message' => 'Book not found.'
            ], 404);
        });

        return response()->json($book);
    }

    // Créer un nouveau livre dans la bdd.
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'isbn' => 'required|unique:books|string|max:255',
            'title' => 'required|string|max:255',
            'author' => 'required|integer|max:255',
            'editor' => 'required|integer|max:255',
            'keyword' => 'required|integer|max:255',
            'summary' => 'required|string|max:500',
            'publish_year' => 'required|integer|min:0|max:9999',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $book = Books::create($request->all());
        return response()->json($book, 201);
    }
}
