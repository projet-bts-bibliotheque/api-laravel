<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'books';
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

    protected $casts = [
        'keywords' => 'array'
    ];
}
