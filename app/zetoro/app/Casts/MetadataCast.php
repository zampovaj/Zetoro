<?php

namespace App\Casts;

use App\DTOs\ArticleMetadataDto;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class MetadataCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            new ArticleMetadataDto;
        }

        $data = json_decode($value, true);

        return new ArticleMetadataDto(
            title: $data['title'] ?? null,
            doi: $data['doi'] ?? null,
            authors: $data['authors'] ?? [],
            page_count: $data['page_count'] ?? null,
            type: $data['type'] ?? 'article',
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value == null) {
            return null;
        }

        return json_encode($value);
    }
}
