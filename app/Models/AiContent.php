<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiContent extends Model
{
    protected $fillable = [
        'customer_id','site_id','template_id','title','tone','provider','status','inputs','body_md','error','scheduled_at'
    ];

    protected $casts = [
        'inputs' => 'array',
        'scheduled_at' => 'datetime',
    ];

    public function template(): BelongsTo { return $this->belongsTo(ContentTemplate::class); }
    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function site(): BelongsTo { return $this->belongsTo(Site::class); }
}
