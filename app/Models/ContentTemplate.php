<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentTemplate extends Model
{
    protected $fillable = ['slug','name','provider','max_tokens','temperature','visibility'];
}
