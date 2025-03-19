<?php

namespace App\Http\Controllers;

use Enums\Status;
use App\Models\Editors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EditorsController extends Controller
{
    public static function getEditor($id) {
        return Editors::where('id', '=', $id)->firstOr(function () {
            return Status::NOT_FOUND;
        });
    }

    private function validate($request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
        ]);

        if($validator->fails()) {
            return $validator->errors();
        }

        return true;
    }

    // Récupérer la liste de tous les éditeurs.
    public function index() {
        $editors = Editors::all();
        return response()->json($editors);
    }

    // Récupérer le livre avec l'ID passé en paramètre.
    public function show($id) {
        $editor = $this->getEditor($id);
        if($editor == Status::NOT_FOUND) return response()->json([
            "message" => "Editor not found"
        ], 404);

        return response()->json($editor);
    }

    // Créer un nouvel éditeur dans la bdd.
    public function store(Request $request) {
        $validated = $this->validate($request);
        if(!$validated) return response()->json($validated, 400);

        $editor = Editors::create($request->all());
        return response()->json($editor, 201);
    }

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
