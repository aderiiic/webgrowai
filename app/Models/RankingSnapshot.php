<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankingSnapshot extends Model
{
    protected $fillable = [
        'site_id','wp_post_id','wp_type','url','keyword','position','serp_link','device','locale','checked_at'
    ];

    protected $casts = ['checked_at' => 'datetime'];
}
