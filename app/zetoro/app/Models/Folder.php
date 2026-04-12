<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
}
