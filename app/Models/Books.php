<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant les livres
 */
class Books extends Model
{
    use HasFactory;

    /**
     * Désactive les colonnes created_at et updated_at
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Nom de la table associée au modèle
     *
     * @var string
     */
    protected $table = 'books';
    
    /**
     * Attributs pouvant être assignés en masse
     *
     * @var array
     */
    protected $fillable = [
        'isbn',
        'title',
        'thumbnail',
        'average_rating',
        'ratings_count',
        'author',
        'editor',
        'keywords',
        'summary',
        'publish_year'
    ];

    /**
     * Conversion des types d'attributs
     *
     * @var array
     */
    protected $casts = [
        'keywords' => 'array'
    ];
}