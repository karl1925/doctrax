<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title', 
        'description', 
        'reference',
        'priority', 
        'creator_id', 
        'target_date', 
        'status'
    ];

    protected $casts = [
        'target_date' => 'datetime',
    ];

    /**
     * Override the Eloquent Builder to customize eager loading constraints.
     */
    public function newEloquentBuilder($query)
    {
        return new class($query) extends Builder {
            /**
             * Add constraints to the query for eager loading.
             * This runs when the Document model is being loaded as a relation.
             */
            public function addEagerConstraints(array $models)
            {
                // Custom logic can be placed here. 
                // By default, it gathers the primary keys of the parent models.
                return parent::whereIn(
                    $this->model->getTable().'.'.$this->model->getKeyName(),
                    collect($models)->pluck($this->model->getKeyName())->toArray()
                );
            }
        };
    }

    public function owner(): BelongsTo 
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function participants(): HasMany 
    {
        return $this->hasMany(DocumentParticipant::class)->orderBy('order');
    }

    public function attachments(): HasMany 
    {
        return $this->hasMany(DocumentAttachment::class)->orderBy('file_name');
    }

    /**
     * Combined history relationship (removed duplicate).
     */
    public function history(): HasMany 
    {
        return $this->hasMany(DocumentHistory::class)->latest();
    }

    /**
     * Helper to find whose turn it is.
     */
    public function currentReviewer(): string
    {
        $participant = $this->participants()
            ->where('status', 'active')
            ->first();

        return $participant ? ($participant->user->name ?? 'Unknown User') : 'N/A';
    }

    /**
     * Get the name of the participant who currently has the 'revision' status.
     *
     * @return string|null
     */
    public function currentRevision(): ?string
    {
        // We look for the first participant marked with 'revision' status.
        // We use optional() or null-safe operator to prevent errors if not found.
        $participant =  $this->participants()
            ->where('status', 'revision')
            ->first();

        return $participant ? ($participant->user->name ?? 'Unknown User') : 'N/A';
    }

    /**
     * Get the date the current authenticated user signed this specific document.
     * Uses the 'history' relationship for consistency.
     */
    public function getUserSignedAtAttribute()
    {
        return $this->history
            ->where('user_id', Auth::id())
            ->where('action', 'document_signed')
            ->first()?->created_at;
    }
}