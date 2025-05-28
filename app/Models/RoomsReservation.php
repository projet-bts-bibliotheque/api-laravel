<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomsReservation extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * Le nom de la table associée au modèle
     *
     * @var string
     */
    protected $table = 'rooms_reservations';

    /**
     * Les attributs qui sont assignables en masse
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'room_id',
        'date'
    ];

    /**
     * Les attributs qui doivent être convertis
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'room_id' => 'integer',
        'date' => 'date'
    ];

    /**
     * Relation avec l'utilisateur
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la salle
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo(Rooms::class);
    }
}