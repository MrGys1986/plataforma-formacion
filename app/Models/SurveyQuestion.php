<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyQuestion extends Model
{
    use HasPublicId;

    protected $guarded = [];

    public function answerOptions(): array
    {
        if ($this->question_type === 'escala') {
            return array_combine(range(1, 5), range(1, 5));
        }

        $options = $this->options ?? [];

        return array_is_list($options) && $options !== []
            ? array_combine($options, $options)
            : $options;
    }

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'is_required' => 'boolean',
        ];
    }
}
