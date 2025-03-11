<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editors extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'editors';
    protected $fillable = [
        'name',
        'address',
        'thumbnail',
    ];
}
