<?php

namespace App\Http\Controllers;

use Enums\Status;
use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/**
 * Gère les fonctionnalités d'authentification et de gestion des utilisateurs
 * Inclut l'inscription, connexion, gestion de profil et réinitialisation de mot de passe
 */
class AuthController extends Controller {

    /**
     * Récupère un utilisateur par son ID
     * 
     * @param int $id ID de l'utilisateur à rechercher
     * @return mixed L'utilisateur trouvé ou Status::NOT_FOUND
     */
    private static function getUser($id) {
        return User::where('id', '=', $id)->firstOr(function () {
            return Status::NOT_FOUND;
        });
    }

    /**
     * Récupère la liste de tous les utilisateurs
     * 
     * @param Request $request La requête HTTP entrante
     * @return \Illuminate\Http\JsonResponse Liste des utilisateurs au format JSON
     */
    public function index(Request $request) {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Récupère les informations de l'utilisateur authentifié
     * 
     * @param Request $request La requête HTTP entrante
     * @return mixed Données de l'utilisateur authentifié
     */
    public function me(Request $request) {
        return $request->user();
    }

    /**
     * Enregistre un nouvel utilisateur
     * 
     * @param Request $request La requête HTTP entrante avec les données d'inscription
     * @return \Illuminate\Http\JsonResponse Token d'accès ou message d'erreur
     */
    public function register(Request $request) {
        $validate = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors()
            ], 400);
        }

        $role = 0;
        if (!User::exists()) {
            $role = 2; // Si c'est le premier utilisateur, il devient administrateur
        }

        $user = User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'address' => $request['address'],
            'phone' => $request['phone'],
            'role' => $role,
        ]);

        $token = $user->createToken('auth_token', ['*'], now()->addHours(2))->plainTextToken;

        event(new Registered($user));

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Authentifie un utilisateur et génère un token
     * 
     * @param Request $request La requête HTTP entrante avec email et mot de passe
     * @return \Illuminate\Http\JsonResponse Token d'accès ou message d'erreur
     */
    public function login(Request $request) {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token', ['*'], now()->addHours(2))->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Met à jour les informations d'un autre utilisateur
     * 
     * @param Request $request La requête HTTP entrante avec les nouvelles données
     * @param int $id ID de l'utilisateur à modifier
     * @return \Illuminate\Http\JsonResponse Message de confirmation ou d'erreur
     */
    public function updateOther(Request $request, $id) {
        $validate = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'role' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors()
            ], 400);
        }

        $user = $this->getUser($id);
        if($user == Status::NOT_FOUND) return response()->json([
            "message" => "User not found"
        ], 404);

        if ($request['email'] !== $user->email && User::where('email', $request['email'])->exists()) {
            return response()->json([
                'message' => 'The provided email is already in use by another account.'
            ], 400);
        }

        $user->update($request->all());

        event(new Registered($user));

        return response()->json([
            'message' => 'The user has been updated.'
        ], 200);
    }

    /**
     * Met à jour les informations de l'utilisateur connecté
     * 
     * @param Request $request La requête HTTP entrante avec les nouvelles données
     * @return \Illuminate\Http\JsonResponse Message de confirmation ou d'erreur
     */
    public function update(Request $request) {
        $validate = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors()
            ], 400);
        }

        $user = $request->user();

        if ($request['email'] !== $user->email && User::where('email', $request['email'])->exists()) {
            return response()->json([
                'message' => 'The provided email is already in use by another account.'
            ], 400);
        }

        $user->update([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'address' => $request['address'],
            'phone' => $request['phone'],
        ]);

        event(new Registered($user));

        return response()->json([
            'message' => 'The user has been updated.'
        ], 200);
    }

    /**
     * Envoie un lien de réinitialisation de mot de passe
     * 
     * @param Request $request La requête HTTP entrante avec l'email
     * @return \Illuminate\Http\JsonResponse Message de confirmation ou d'erreur
     */
    public function forgotPassword(Request $request) {
        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors()
            ], 400);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::ResetLinkSent
                    ? response()->json(['message' => __($status)])
                    : response()->json(['message' => __($status)], 400);
    }

    /**
     * Envoie un lien de réinitialisation de mot de passe pour un autre utilisateur
     * 
     * @param Request $request La requête HTTP entrante
     * @param int $id ID de l'utilisateur concerné
     * @return \Illuminate\Http\JsonResponse Message de confirmation ou d'erreur
     */
    public function forgotPasswordOther(Request $request, $id) {
        $user = $this->getUser($id);
        if($user == Status::NOT_FOUND) return response()->json([
            "message" => "User not found"
        ], 404);

        $status = Password::sendResetLink(
            $user->only('email')
        );

        return $status === Password::ResetLinkSent
                    ? response()->json(['message' => "Email sent."])
                    : response()->json(['message' => __($status)], 400);
    }

    /**
     * Réinitialise le mot de passe d'un utilisateur
     * 
     * @param Request $request La requête HTTP entrante avec email, token et nouveau mot de passe
     * @return \Illuminate\Http\JsonResponse Message de confirmation ou d'erreur
     */
    public function resetPassword(Request $request) {
        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'token' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PasswordReset
                    ? response()->json(['message' => __($status)])
                    : response()->json(['message' => __($status)], 400);
    }

    /**
     * Envoie un email de vérification à l'utilisateur connecté
     * 
     * @param Request $request La requête HTTP entrante
     * @return mixed Redirection avec message de confirmation
     */
    public function sendVerifyEmail (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    }

    /**
     * Valide l'email d'un utilisateur après clic sur le lien de vérification
     * 
     * @param EmailVerificationRequest $request La requête de vérification d'email
     * @return \Illuminate\Http\JsonResponse Message de confirmation
     */
    public function verifyEmail(EmailVerificationRequest $request) {
        $request->fulfill();

        return response()->json(['message' => 'Email verified']);
    }

    /**
     * Supprime un autre utilisateur
     * 
     * @param Request $request La requête HTTP entrante
     * @param int $id ID de l'utilisateur à supprimer
     * @return \Illuminate\Http\JsonResponse Message de confirmation ou d'erreur
     */
    public function deleteOther(Request $request, $id) {
        $user = $this->getUser($id);
        if($user == Status::NOT_FOUND) return response()->json([
            "message" => "User not found"
        ], 404);

        $user->delete();

        return response()->json([
            'message' => 'The user has been deleted.'
        ], 200);
    }

    /**
     * Supprime l'utilisateur connecté et ses tokens d'accès
     * 
     * @param Request $request La requête HTTP entrante
     * @return \Illuminate\Http\JsonResponse Message de confirmation
     */
    public function delete(Request $request) {
        $request->user()->tokens()->delete();
        $request->user()->delete();

        return response()->json([
            'message' => 'The user has been deleted.'
        ], 200);
    }
}