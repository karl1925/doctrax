<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentHistory extends Model
{
    protected $fillable = ['document_id', 'user_id', 'action', 'remarks'];

    public function user(): BelongsTo
    {
        // This links &#39;user_id&#39; in this table to the &#39;id&#39; in the &#39;users&#39; table
        return $this->belongsTo(User::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
