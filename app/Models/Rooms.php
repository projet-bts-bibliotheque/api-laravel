<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    /**
     * Le nom de la table associée au modèle
     *
     * @var string
     */
    protected $table = 'rooms';

    /**
     * Les attributs qui sont assignables en masse
     *
     * @var array
     */
    protected $fillable = [
        'places'
    ];
}