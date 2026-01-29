<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'publisher',
        'publish_date',
        'pages',
        'summary'
    ];

    protected $casts = [
        'publish_date' => 'date',
    ];

}
