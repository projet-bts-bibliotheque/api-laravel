<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant les éditeurs de livres
 */
class Editors extends Model
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
    protected $table = 'editors';
    
    /**
     * Attributs pouvant être assignés en masse
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}