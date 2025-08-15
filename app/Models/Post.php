<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title','slug','excerpt','body_md','published_at'];
    protected $casts = ['published_at' => 'datetime'];
}
