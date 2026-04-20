<?php

namespace App\Models;

use App\Observers\FileObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[ObservedBy(FileObserver::class)]
class File extends Model
{
    use HasUlids;

    protected $fillable = [
        'article_id',
        'name',
        'path',
    ];

    public function getAbsolutePath(): string
    {
        return Storage::disk('pdf_vault')->path($this->path);
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
