<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    
    // Explicitly define the table name to prevent errors if Laravel gets confused
    protected $table = 'posts'; 

    protected $fillable = [
        'title',
        'content',
        'slug',
        'image',
        'user_id',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
    
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}