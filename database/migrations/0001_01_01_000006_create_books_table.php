<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour créer la table des livres
 */
return new class extends Migration
{
    /**
     * Exécute la migration pour créer la table books
     * Cette table stocke les informations complètes sur les livres
     * et leurs relations avec les auteurs et éditeurs
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->string("isbn")->primary()->unique();  // ISBN comme clé primaire au lieu d'un ID auto-incrémenté
            $table->string("title");  // Titre du livre
            $table->longText("thumbnail");  // URL ou chemin vers la couverture du livre
            $table->float("average_rating");  // Note moyenne du livre (sur 5)
            $table->integer("ratings_count");  // Nombre total d'évaluations

            // Relation avec la table authors (clé étrangère)
            $table->unsignedBigInteger("author");
            $table->foreign("author")->references("id")->on("authors");

            // Relation avec la table editors (clé étrangère)
            $table->unsignedBigInteger("editor");
            $table->foreign("editor")->references("id")->on("editors");

            $table->json("keywords");  // Mots-clés stockés en format JSON
            $table->integer('pages'); // Nombre de pages du livre

            $table->text("summary");  // Résumé du livre (texte long)
            $table->integer("publish_year");  // Année de publication
            
            // Pas de timestamps (created_at/updated_at) pour cette table
        });
    }

    /**
     * Annule la migration en supprimant la table books
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};