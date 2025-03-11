<?php

namespace App\Http\Controllers;

use Enums\Status;
use App\Models\Authors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorsController extends Controller
{

    public static function getAuthor($id) {
        return Authors::where('id', '=', $id)->firstOr(function () {
            return Status::NOT_FOUND;
        });
    }

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

    // Récupérer la liste de tous les auteurs.
    public function index() {
        $authors = Authors::all();
        return response()->json($authors);
    }

    // Récupérer le livre avec l'ID passé en paramètre.
    public function show($id) {
        $author = $this->getAuthor($id);
        if($author == Status::NOT_FOUND) return response()->json([
            "message" => "Author not found"
        ], 404);

        return response()->json($author);
    }

    // Créer un nouvel auteur dans la bdd.
    public function store(Request $request) {
        $validated = $this->validate($request);
        if(!$validated) return response()->json($validated, 400);

        $author = Authors::create($request->all());
        return response()->json($author, 201);
    }


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

    public function destroyOther($id) {
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
