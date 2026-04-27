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
 * @property string $file_id
 * @property array<array-key, mixed> $rectangles
 * @property int $page
 * @property string $highlight_color
 * @property string|null $note
 * @property string $text
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\File $file
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereHighlightColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation wherePage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereRectangles($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Annotation whereUpdatedAt($value)
 */
	class Annotation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property mixed $metadata
 * @property string|null $url
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $files
 * @property-read int|null $files_count
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
 * @property string $article_id
 * @property string $name
 * @property string $path
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Annotation> $annotations
 * @property-read int|null $annotations_count
 * @property-read \App\Models\Article $article
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereArticleId($value)
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
 * @property-read Collection|Article[] $articles
 * @property-read Collection|File[] $files
 * @property string $id
 * @property string $name
 * @property string|null $parent_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read int|null $articles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Folder> $children
 * @property-read int|null $children_count
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
 * @property int $id
 * @property string|null $last_active_folder_id
 * @property string $default_highlight_color
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Folder|null $folder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Settings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Settings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Settings query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Settings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Settings whereDefaultHighlightColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Settings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Settings whereLastActiveFolderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Settings whereUpdatedAt($value)
 */
	class Settings extends \Eloquent {}
}

