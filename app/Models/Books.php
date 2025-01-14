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
        'author',
        'editor',
        'keyword',
        'summary',
        'publish_year'
    ];
}
