<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BooksReservation extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    /**
     * Le nom de la table associée au modèle
     *
     * @var string
     */
    protected $table = 'books_reservations';

    /**
     * Les attributs qui sont assignables en masse
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'start',
        'return_date',
        'reminder_mail_sent'
    ];

    /**
     * Les attributs qui doivent être convertis
     *
     * @var array
     */
    protected $casts = [
        'start' => 'date',
        'return_date' => 'date',
        'reminder_mail_sent' => 'boolean'
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
     * Relation avec le livre
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function book()
    {
        return $this->belongsTo(Books::class, 'book_id', 'isbn');
    }
}