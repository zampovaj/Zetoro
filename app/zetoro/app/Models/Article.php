<?php

namespace App\Models;

use App\Casts\MetadataCast;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function article(): HasOne
    {
        return $this->hasOne(Article::class);
    }
}
