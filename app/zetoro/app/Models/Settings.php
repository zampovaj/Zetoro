<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Settings extends Model
{
    use HasUlids;

    protected $fillable = [
        'last_active_folder_id',
        'default_highlight_color',
    ];

    public static function current(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'last_active_folder_id' => null,
                'default_highlight_color' => '#ffed29',
            ]
        );
    }

    public function folder(): HasOne
    {
        return $this->hasOne(Folder::class);
    }
}
