<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property string $id
 * @property string $article_id
 * @property float $x_min
 * @property float $y_min
 * @property float $x_max
 * @property float $y_max
 * @property string|null $highlight_color
 * @property string|null $note
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Article|null $article
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereHighlightColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereXMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereXMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereYMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereYMin($value)
 */
	class Annotation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property mixed $metadata
 * @property string $url
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read Article|null $article
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Folder> $folders
 * @property-read int|null $folders_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Article whereUrl($value)
 */
	class Article extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string $path
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereUpdatedAt($value)
 */
	class File extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\File[] $files
 * @property string $id
 * @property string $name
 * @property string|null $parent_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read int|null $articles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Folder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Folder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Folder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Folder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Folder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Folder whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Folder whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Folder whereUpdatedAt($value)
 */
	class Folder extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Folder|null $folder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Settings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Settings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Settings query()
 */
	class Settings extends \Eloquent {}
}

