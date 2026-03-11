<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Notifications\GmailResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'profile_photo_path',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function createdDocuments(): HasMany
    {
        return $this->hasMany(Document::class, 'creator_id');
    }

    public function pendingDocuments(): HasMany
    {
        return $this->hasMany(Document::class, 'current_signer_id');
    }

    public function signedDocuments(): BelongsToMany
    {
        return $this->belongsToMany(Document::class)
            ->withPivot('signed_at', 'status', 'signature_path')
            ->withTimestamps()
            ->wherePivotNotNull('signed_at');
    }

    public function documentsToSign(): BelongsToMany
    {
        return $this->belongsToMany(Document::class)
            ->withPivot('signed_at', 'status')
            ->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function canReceiveRequests(): bool
    {
        return Setting::where('setting', 'receiver')
            ->where('user_id', auth()->id())
            ->exists();
    } 

     public function canMonitorRequests(): bool
    {
        return Setting::where('setting', 'monitorer')
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function canForwardRequests(): bool
    {
        return Setting::where('setting', 'forwarder')
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function canEndorseRequests(): bool
    {
        return Setting::where('setting', 'endorser')
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function isDirector(): bool
    {
        return Setting::where('setting', 'director')
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function isChiefTOD(): bool
    {
        return Setting::where('setting', 'todchief')
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function isChiefAFD(): bool
    {
        return Setting::where('setting', 'afdchief')
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function isSuper(): bool
    {
        return Setting::where('setting', 'super')
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function isOnline()
    {
        return DB::table('sessions')
            ->where('user_id', $this->id)
            ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime'))->getTimestamp())
            ->exists();
    }

    public function preference()
    {
        return $this->hasOne(Preference::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class, 'user_id', 'id');
    }

}