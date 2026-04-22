<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Annotation extends Model
{
    use HasUlids;

    protected $fillable = [
        'file_id',
        'rectangles',
        'page',
        'highlight_color',
        'note',
    ];

    protected $casts = [
        'rectangles' => 'array',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
