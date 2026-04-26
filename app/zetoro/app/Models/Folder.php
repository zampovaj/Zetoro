<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read Collection|Article[] $articles
 * @property-read Collection|File[] $files
 */
class Folder extends Model
{
    use HasUlids;

    protected $fillable = [
        'name',
        'parent_id',
    ];

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    public function allChildren()
    {
        return $this->children()->with(['allChildren', 'articles.files.annotations']);
    }
}
