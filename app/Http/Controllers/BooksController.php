<?php

namespace App\Http\Controllers;

use Enums\Status;
use App\Models\Books;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Gère les opérations CRUD pour les livres
 */
class BooksController extends Controller
{
    /**
     * Récupère un livre par son ISBN
     * 
     * @param string $id ISBN du livre à rechercher
     * @return mixed Le livre trouvé ou Status::NOT_FOUND
     */
    private function getBook($id) {
        return Books::where('isbn', '=', $id)->firstOr(function () {
            return Status::NOT_FOUND;
        });
    }

    /**
     * Valide les données d'un livre
     * 
     * @param Request $request La requête contenant les données à valider
     * @return mixed true si valide, sinon les erreurs de validation
     */
    private function validate($request) {
        $validator = Validator::make($request->all(), [
            'isbn' => 'required|unique:books|string|max:255',
            'title' => 'required|string|max:255',
            'thumbnail' => 'required|string|max:255',
            'author' => 'required|integer|max:255',
            'editor' => 'required|integer|max:255',
            'average_rating' => 'required|numeric|min:0|max:5',
            'ratings_count' => 'required|integer|min:0',
            'keyword' => 'required|array',
            'summary' => 'required|string|max:500',
            'pages' => 'required|integer|min:1|max:10000',
            'publish_year' => 'required|integer|min:0|max:9999',
        ]);

        if($validator->fails()) {
            return $validator->errors();
        }

        return true;
    }

    /**
     * Récupère la liste de tous les livres
     * 
     * @return \Illuminate\Http\JsonResponse Liste des livres au format JSON
     */
    public function index() {
        $books = Books::all();
        return response()->json($books);
    }

    /**
     * Récupère un livre spécifique par son ISBN
     * 
     * @param string $id ISBN du livre à récupérer
     * @return \Illuminate\Http\JsonResponse Données du livre ou message d'erreur
     */
    public function show($id) {
        $book = $this->getBook($id);
        if($book == Status::NOT_FOUND) return response()->json([
            "message" => "Book not found"
        ], 404);

        return response()->json($book);
    }

    /**
     * Crée un nouveau livre
     * 
     * @param Request $request La requête contenant les données du livre
     * @return \Illuminate\Http\JsonResponse Données du nouveau livre ou erreurs
     */
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

    /**
     * Met à jour un livre existant
     * 
     * @param Request $request La requête contenant les nouvelles données
     * @param string $id ISBN du livre à modifier
     * @return \Illuminate\Http\JsonResponse Données mises à jour ou message d'erreur
     */
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

    /**
     * Supprime un livre
     * 
     * @param string $id ISBN du livre à supprimer
     * @return \Illuminate\Http\JsonResponse Message de confirmation ou d'erreur
     */
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