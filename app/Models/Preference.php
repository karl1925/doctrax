<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'external_email_notify_received',
        'external_email_notify_updated',
        'external_email_notify_completed',
        'internal_email_notify_received',
        'internal_email_notify_returned',
        'internal_email_notify_reviewed',
        'internal_email_notify_completed',
        'internal_email_notify_rejected',
    ];

    protected $casts = [
        'external_email_notify_received' => 'boolean',
        'external_email_notify_updated' => 'boolean',
        'external_email_notify_completed' => 'boolean',
        'internal_email_notify_received' => 'boolean',
        'internal_email_notify_returned' => 'boolean',
        'internal_email_notify_reviewed' => 'boolean',
        'internal_email_notify_completed' => 'boolean',
        'internal_email_notify_rejected' => 'boolean',
    ];

    /**
     * Relationship: Preference belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}