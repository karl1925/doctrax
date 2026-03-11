<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalHistory extends Model
{
    protected $fillable = ['external_id', 'user_id', 'action', 'remarks'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function external(): BelongsTo
    {
        return $this->belongsTo(External::class);
    }
}
