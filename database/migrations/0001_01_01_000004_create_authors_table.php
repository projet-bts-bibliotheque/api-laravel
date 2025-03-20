<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour créer la table des auteurs
 */
return new class extends Migration
{
    /**
     * Exécute la migration pour créer la table authors
     * Cette table stocke les informations de base sur les auteurs de livres
     */
    public function up(): void
    {
        Schema::create('authors', function(Blueprint $table) {
            $table->id()->unique();  // Clé primaire auto-incrémentée avec contrainte unique
            $table->string("firstname");  // Prénom de l'auteur
            $table->string("lastname");  // Nom de famille de l'auteur
        });
    }

    /**
     * Annule la migration en supprimant la table authors
     */
    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};