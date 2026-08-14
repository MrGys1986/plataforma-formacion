<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\VisibleToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileUpload extends Model
{
    use HasPublicId, SoftDeletes, VisibleToUser;

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

    public function certificateTemplates(): HasMany
    {
        return $this->hasMany(CertificateTemplate::class, 'template_file_id');
    }

    public function courseCovers(): HasMany
    {
        return $this->hasMany(TrainingProgram::class, 'cover_file_id');
    }

    public function activityCovers(): HasMany
    {
        return $this->hasMany(Activity::class, 'cover_file_id');
    }

    public function userAvatars(): HasMany
    {
        return $this->hasMany(User::class, 'avatar_file_id');
    }

    public function isOrphan(): bool
    {
        return ! $this->evidences()->exists()
            && ! $this->certificates()->exists()
            && ! $this->digitalResources()->exists()
            && ! $this->paymentProofs()->exists()
            && ! $this->certificateTemplates()->exists()
            && ! $this->courseCovers()->exists()
            && ! $this->activityCovers()->exists()
            && ! $this->userAvatars()->exists();
    }

    public function optimizedImageUrl(int $width = 800, int $height = 450): string
    {
        if ($this->disk !== 'cloudinary' || $this->resource_type !== 'image') {
            return $this->temporaryDownloadUrl();
        }

        $cloud = rawurlencode((string) config('services.cloudinary.cloud_name'));
        $path = collect(explode('/', $this->path))->map(rawurlencode(...))->implode('/');

        $version = $this->version ? '/v'.$this->version : '';

        $format = $this->extension ? '.'.rawurlencode($this->extension) : '';

        return "https://res.cloudinary.com/{$cloud}/image/upload/f_auto,q_auto,c_fill,g_auto,w_{$width},h_{$height}{$version}/{$path}{$format}";
    }

    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'delete_after' => 'datetime',
        ];
    }

    public function temporaryDownloadUrl(?int $minutes = null): string
    {
        return URL::temporarySignedRoute(
            'files.download',
            now()->addMinutes($minutes ?? config('security.signed_url_minutes', 10)),
            ['fileUpload' => $this],
        );
    }

    public function temporaryPreviewUrl(?int $minutes = null): string
    {
        return URL::temporarySignedRoute(
            'files.preview',
            now()->addMinutes($minutes ?? config('security.signed_url_minutes', 10)),
            ['fileUpload' => $this],
        );
    }
}
