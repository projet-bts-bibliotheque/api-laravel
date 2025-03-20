<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour créer la table des éditeurs
 */
return new class extends Migration
{
    /**
     * Exécute la migration pour créer la table editors
     * Cette table stocke les informations de base sur les maisons d'édition
     */
    public function up(): void
    {
        Schema::create('editors', function (Blueprint $table) {
            $table->id()->unique();  // Clé primaire auto-incrémentée avec contrainte unique
            $table->string("name");  // Nom de la maison d'édition
            // Note: le champ address et thumbnail mentionnés dans le modèle ne sont pas présents ici
            // Pas de timestamps (created_at/updated_at) pour cette table
        });
    }

    /**
     * Annule la migration en supprimant la table editors
     */
    public function down(): void
    {
        Schema::dropIfExists('editors');
    }
};