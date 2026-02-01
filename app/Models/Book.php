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

    public function users()
    {
        return $this->belongsToMany(User::class, 'reviews', 'id_book', 'id_user')
                    ->withPivot('add_date', 'read_date', 'comment', 'rating')
                    ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_book');
    }
}
