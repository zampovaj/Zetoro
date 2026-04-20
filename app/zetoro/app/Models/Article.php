<?php

namespace App\Models;

use App\Casts\MetadataCast;
use App\Observers\ArticleObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(ArticleObserver::class)]
class Article extends Model
{
    use HasUlids;

    protected $fillable = [
        'metadata',
        'url',
    ];

    protected $casts = [
        'metadata' => MetadataCast::class,
    ];

    public function folders(): BelongsToMany
    {
        return $this->belongsToMany(Folder::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
