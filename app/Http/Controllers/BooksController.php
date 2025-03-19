<?php

namespace App\Http\Controllers;

use Enums\Status;
use App\Models\Books;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BooksController extends Controller
{

    private function getBook($id) {
        return Books::where('isbn', '=', $id)->firstOr(function () {
            return Status::NOT_FOUND;
        });
    }

    private function validate($request) {
        $validator = Validator::make($request->all(), [
            'isbn' => 'required|unique:books|string|max:255',
            'title' => 'required|string|max:255',
            'thumbnails' => 'required|string|max:255',
            'author' => 'required|integer|max:255',
            'editor' => 'required|integer|max:255',
            'average_rating' => 'required|numeric|min:0|max:5',
            'ratings_count' => 'required|integer|min:0',
            'keyword' => 'required|array',
            'summary' => 'required|string|max:500',
            'publish_year' => 'required|integer|min:0|max:9999',
        ]);

        if($validator->fails()) {
            return $validator->errors();
        }

        return true;
    }

    // Récupérer la liste de tous les livres.
    public function index() {
        $books = Books::all();
        return response()->json($books);
    }

    // Récupérer le livre avec l'ID passé en paramètre.
    public function show($id) {
        $book = $this->getBook($id);
        if($book == Status::NOT_FOUND) return response()->json([
            "message" => "Book not found"
        ], 404);

        return response()->json($book);
    }

    // Créer un nouveau livre dans la bdd.
    public function store(Request $request) {
        $validated = $this->validate($request);
        if(!$validated) return response()->json($validated, 400);

        $book = $this->getBook($request->isbn);
        if($book != Status::NOT_FOUND) return response()->json([
            'message' => 'Book with ISBN already exists.'
        ], 409);

        $author = AuthorsController::getAuthor($request->author);
        if($author == Status::NOT_FOUND) return response()->json([
            'message' => "Author not found"
        ], 404);

        $book = Books::create($request->all());
        return response()->json($book, 201);
    }


    Public function update(Request $request, $id){
        $validated = $this->validate($request);
        if(!$validated) return response()->json($validated, 400);

        $book = $this->getBook($id);
        if($book == Status::NOT_FOUND) return response()->json([
            "message" => "Book not found"
        ], 404);

        Books::where("isbn", "=", $id)->update($request->all());

        return response()->json($this->getBook($id));
    }

    public function destroy($id) {
        $book = $this->getBook($id);
        if($book == Status::NOT_FOUND) return response()->json([
            "message" => "Book not found"
        ], 404);

        $book->delete();

        return response()->json([
            'message' => 'The book has been deleted.'
        ], 200);
    }
}
