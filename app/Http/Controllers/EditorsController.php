<?php

namespace App\Http\Controllers;

use Enums\Status;
use App\Models\Editors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Gère les opérations CRUD pour les éditeurs
 */
class EditorsController extends Controller
{
    /**
     * Récupère un éditeur par son ID
     * 
     * @param int $id ID de l'éditeur à rechercher
     * @return mixed L'éditeur trouvé ou Status::NOT_FOUND
     */
    public static function getEditor($id) {
        return Editors::where('id', '=', $id)->firstOr(function () {
            return Status::NOT_FOUND;
        });
    }

    /**
     * Valide les données d'un éditeur
     * 
     * @param Request $request La requête contenant les données à valider
     * @return mixed true si valide, sinon les erreurs de validation
     */
    private function validate($request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:editors|between:2,100',
        ]);

        if($validator->fails()) {
            return $validator->errors();
        }

        return true;
    }

    /**
     * Récupère la liste de tous les éditeurs
     * 
     * @return \Illuminate\Http\JsonResponse Liste des éditeurs au format JSON
     */
    public function index() {
        $editors = Editors::all();
        return response()->json($editors);
    }

    /**
     * Récupère un éditeur spécifique par son ID
     * 
     * @param int $id ID de l'éditeur à récupérer
     * @return \Illuminate\Http\JsonResponse Données de l'éditeur ou message d'erreur
     */
    public function show($id) {
        $editor = $this->getEditor($id);
        if($editor == Status::NOT_FOUND) return response()->json([
            "message" => "Editor not found"
        ], 404);

        return response()->json($editor);
    }

    /**
     * Crée un nouvel éditeur
     * 
     * @param Request $request La requête contenant les données de l'éditeur
     * @return \Illuminate\Http\JsonResponse Données du nouvel éditeur ou erreurs de validation
     */
    public function store(Request $request) {
        $validated = $this->validate($request);
        if(!$validated) return response()->json($validated, 400);

        $editor = Editors::create($request->all());
        return response()->json($editor, 201);
    }

    /**
     * Met à jour un éditeur existant
     * 
     * @param Request $request La requête contenant les nouvelles données
     * @param int $id ID de l'éditeur à modifier
     * @return \Illuminate\Http\JsonResponse Données mises à jour ou message d'erreur
     */
    public function update(Request $request, $id) {
        $validated = $this->validate($request);
        if(!$validated) return response()->json($validated, 400);

        $editor = $this->getEditor($id);
        if($editor == Status::NOT_FOUND) return response()->json([
            "message" => "Editor not found"
        ], 404);

        $editor->update($request->all());
        return response()->json($editor);
    }

    /**
     * Supprime un éditeur
     * 
     * @param int $id ID de l'éditeur à supprimer
     * @return \Illuminate\Http\JsonResponse Message de confirmation ou d'erreur
     */
    public function destroy($id) {
        $editor = $this->getEditor($id);
        if($editor == Status::NOT_FOUND) return response()->json([
            "message" => "Editor not found"
        ], 404);

        $editor->delete();
        return response()->json([
            "message" => "Editor deleted"
        ]);
    }
}