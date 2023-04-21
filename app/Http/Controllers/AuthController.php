<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Cellier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function sauvegarder(Request $request)
    {
        // Changer le nom de la variable de confirmation de mot de passe
        $request->merge(['mdp_confirmation' => $request->get('conf_mdp')]);

        // Valider les données
        $request->validate([
            'nom' => 'required',
            'courriel' => 'required|email',
            'mdp' => 'required|min:3',
            'mdp_confirmation' => 'required|same:mdp'
        ]);

        // Créer l'utilisateur
        $utilisateur = new Utilisateur;

        // Sauvegarder les données
        $utilisateur->fill($request->all());
        $utilisateur->mdp = Hash::make($request->mdp);
        $utilisateur->save();

        //Créer un celier pour l'utilisateur

        $cellier = new Cellier;
        $cellier->nom = "Cellier de " . $utilisateur->nom;
        $cellier->utilisateur_id = $utilisateur->id;
        $cellier->save();

        $token = JWTAuth::fromUser($utilisateur);

        // Retourner une réponse
        return response()->json([
            'status' => 'success',
            'user' => $utilisateur,
            'token' => $token,

        ], 201);
    }

    /**
     * Connecte un utilisateur
     */
    public function authentifier(Request $request)
    {
        // Valider les données
        $request->validate([
            'courriel' => 'required|email',
            'mdp' => 'required|min:3'
        ]);

        // Chercher l'utilisateur
        $utilisateur = Utilisateur::where('courriel', $request->courriel)->first();

        // Vérifier si l'utilisateur existe
        if (!$utilisateur) {
            return response()->json(['code' => 'courriel_non_trouvé']);
        }
        // Vérifier si le mot de passe est correct
        if (!Hash::check($request->mdp, $utilisateur->mdp)) {
            return response()->json(['code' => 'mot_de_passe_incorrect']);
        }
        $token = JWTAuth::fromUser($utilisateur);
        
        // Retourner une réponse
        return response()->json([
            'status' => 'success',
            'utilisateur' => $utilisateur,
            'token' => $token,
        ], 201);
    }

    public function deconnecter(Request $request)
    {
        
            return response()->json([
                'message' => 'Déconnecter avec succès',
            ], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Utilisateur $utilisateur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Utilisateur $utilisateur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Utilisateur $utilisateur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Utilisateur $utilisateur)
    {
        //
    }
}
