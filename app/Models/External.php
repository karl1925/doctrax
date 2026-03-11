<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class External extends Model
{
    use SoftDeletes;
    protected $fillable = ['subject', 'agency', 'contact', 'description', 'reference', 'creator_id', 'assigned_to', 'priority', 'target_date', 'status'];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'target_date' => 'datetime',
    ];

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function history() {
        return $this->hasOne(ExternalHistory::class)
                ->latest('created_at');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ExternalHistory::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ExternalAttachment::class);
    }

}
