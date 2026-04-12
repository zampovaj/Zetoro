<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Annotation extends Model
{
    use HasUlids;

    protected $fillable = [
        'article_id',
        'x_min',
        'y_min',
        'x_max',
        'y_max',
        'highlight_color',
        'note',
    ];

    public function article(): HasOne
    {
        return $this->hasOne(Article::class);
    }
}
