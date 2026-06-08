<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\VisibleToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\URL;

class FileUpload extends Model
{
    use HasPublicId, VisibleToUser;

    protected $guarded = [];

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(Evidence::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function digitalResources(): HasMany
    {
        return $this->hasMany(DigitalResource::class);
    }

    public function paymentProofs(): HasMany
    {
        return $this->hasMany(Payment::class, 'proof_file_id');
    }

    public function temporaryDownloadUrl(?int $minutes = null): string
    {
        return URL::temporarySignedRoute(
            'files.download',
            now()->addMinutes($minutes ?? config('security.signed_url_minutes', 10)),
            ['fileUpload' => $this],
        );
    }
}
