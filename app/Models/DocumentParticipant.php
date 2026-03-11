<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentParticipant extends Model
{
    protected $fillable = ['document_id', 'user_id', 'order', 'status'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
