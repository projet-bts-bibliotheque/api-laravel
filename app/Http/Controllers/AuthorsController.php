<?php

namespace App\Http\Controllers;

use Enums\Status;
use App\Models\Authors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Gère les opérations CRUD pour les auteurs
 */
class AuthorsController extends Controller
{
    /**
     * Récupère un auteur par son ID
     * 
     * @param int $id ID de l'auteur à rechercher
     * @return mixed L'auteur trouvé ou Status::NOT_FOUND
     */
    public static function getAuthor($id) {
        return Authors::where('id', '=', $id)->firstOr(function () {
            return Status::NOT_FOUND;
        });
    }

    /**
     * Valide les données d'un auteur
     * 
     * @param Request $request La requête contenant les données à valider
     * @return mixed true si valide, sinon les erreurs de validation
     */
    private function validate($request) {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|between:2,100',
            'lastname' => 'required|string|between:2,100',
        ]);

        if($validator->fails()) {
            return $validator->errors();
        }

        return true;
    }

    /**
     * Récupère la liste de tous les auteurs
     * 
     * @return \Illuminate\Http\JsonResponse Liste des auteurs au format JSON
     */
    public function index() {
        $authors = Authors::all();
        return response()->json($authors);
    }

    /**
     * Récupère un auteur spécifique par son ID
     * 
     * @param int $id ID de l'auteur à récupérer
     * @return \Illuminate\Http\JsonResponse Données de l'auteur ou message d'erreur
     */
    public function show($id) {
        $author = $this->getAuthor($id);
        if($author == Status::NOT_FOUND) return response()->json([
            "message" => "Author not found"
        ], 404);

        return response()->json($author);
    }

    /**
     * Crée un nouvel auteur
     * 
     * @param Request $request La requête contenant les données de l'auteur
     * @return \Illuminate\Http\JsonResponse Données du nouvel auteur ou erreurs de validation
     */
    public function store(Request $request) {
        $validated = $this->validate($request);
        if(!$validated) return response()->json($validated, 400);

        $author = Authors::create($request->all());
        return response()->json($author, 201);
    }

    /**
     * Met à jour un auteur existant
     * 
     * @param Request $request La requête contenant les nouvelles données
     * @param int $id ID de l'auteur à modifier
     * @return \Illuminate\Http\JsonResponse Données mises à jour ou message d'erreur
     */
    public function update(Request $request, $id){
        $validated = $this->validate($request);
        if(!$validated) return response()->json($validated, 400);

        $author = $this->getAuthor($id);
        if($author == Status::NOT_FOUND) return response()->json([
            "message" => "Author not found"
        ], 404);

        $author->update($request->all());

        return response()->json($this->getAuthor($id));
    }

    /**
     * Supprime un auteur
     * 
     * @param int $id ID de l'auteur à supprimer
     * @return \Illuminate\Http\JsonResponse Message de confirmation ou d'erreur
     */
    public function destroy($id) {
        $author = $this->getAuthor($id);
        if($author == Status::NOT_FOUND) return response()->json([
            "message" => "Author not found"
        ], 404);

        $author->delete();

        return response()->json([
            'message' => 'The author has been deleted.'
        ], 200);
    }
}