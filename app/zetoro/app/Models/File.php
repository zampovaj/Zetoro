<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class File extends Model
{
    use HasUlids;

    protected $fillable = [
        'article_id',
        'name',
        'path',
    ];

    public function path(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                // backward slash is like a special character so it need to type it twice (i dont get it but it works)
                $basePath = rtrim(config('filesystems.pdf_storage_base_path'), '/\\');
                $relativePath = ltrim($value, '/\\');

                return $basePath.DIRECTORY_SEPARATOR.$relativePath;
            },
            set: function (string $value) {
                $basePath = rtrim(config('filesystems.pdf_storage_base_path'), '/\\');
                $cleanedPath = Str::after($value, $basePath);

                return ltrim($cleanedPath, '/\\');
            }
        );
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
