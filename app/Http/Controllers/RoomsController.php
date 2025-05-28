<?php

namespace App\Http\Controllers;

use Enums\Status;
use App\Models\Rooms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Gère les opérations CRUD pour les salles
 */
class RoomsController extends Controller
{
    /**
     * Récupère une salle par son ID
     * 
     * @param int $id ID de la salle à rechercher
     * @return mixed La salle trouvée ou Status::NOT_FOUND
     */
    public function getRoom($id) {
        return Rooms::where('id', '=', $id)->firstOr(function() {
            return Status::NOT_FOUND;
        });
    }

    /**
     * Valide les données d'une salle
     * 
     * @param Request $request La requête contenant les données à valider
     * @return mixed true si valide, sinon les erreurs de validation
     */
    private function validate($request) {
        $validator = Validator::make($request->all(), [
            'places' => 'required|integer|min:1',
        ]);

        if($validator->fails()) {
            return $validator->errors();
        }

        return true;
    }
    
    /**
     * Récupère la liste de toutes les salles
     * 
     * @return \Illuminate\Http\JsonResponse Liste des salles au format JSON
     */
    public function index()
    {
        $rooms = Rooms::all();
        return response()->json($rooms);
    }

    /**
     * Récupère une salle spécifique par son ID
     * 
     * @param int $id ID de la salle à récupérer
     * @return \Illuminate\Http\JsonResponse Données de la salle ou message d'erreur
     */
    public function show($id) {
        $room = $this->getRoom($id);
        if($room == Status::NOT_FOUND) return response()->json([
            'message' => 'Room not found'
        ], 404);

        return response()->json($room);
    }

    /**
     * Crée une nouvelle salle
     * 
     * @param Request $request La requête contenant les données de la salle
     * @return \Illuminate\Http\JsonResponse Données de la nouvelle salle ou erreurs de validation
     */
    public function store(Request $request) {
        $validation = $this->validate($request);
        if($validation !== true) return response()->json($validation, 400);

        $room = Rooms::create([
            'places' => $request->places
        ]);

        return response()->json($room, 201);
    }

    /**
     * Met à jour une salle existante
     * 
     * @param Request $request La requête contenant les nouvelles données
     * @param int $id ID de la salle à modifier
     * @return \Illuminate\Http\JsonResponse Données mises à jour ou message d'erreur
     */
    public function update(Request $request, $id) {
        $validated = $this->validate($request);
        if($validated !== true) return response()->json($validated, 400);

        $room = $this->getRoom($id);
        if($room == Status::NOT_FOUND) return response()->json([
            'message' => 'Room not found'
        ], 404);

        $room->update([
            'places' => $request->places
        ]);

        return response()->json($room);
    }

    /**
     * Supprime une salle
     * 
     * @param int $id ID de la salle à supprimer
     * @return \Illuminate\Http\JsonResponse Message de confirmation ou d'erreur
     */
    public function destroy($id) {
        $editor = $this->getRoom($id);
        if($editor == Status::NOT_FOUND) return response()->json([
            'message' => 'Room not found'
        ], 404);

        $editor->delete();
        
        return response()->json([
            'message' => 'Room deleted'
        ]);
    }
}