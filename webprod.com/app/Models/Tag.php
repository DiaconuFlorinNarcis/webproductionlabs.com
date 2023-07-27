<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as Query;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property int $active
 * @property int|null $category_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Tag newModelQuery()
 * @method static Builder|Tag newQuery()
 * @method static Builder|Tag query()
 * @method static Builder|Tag whereActive($value)
 * @method static Builder|Tag whereCategoryId($value)
 * @method static Builder|Tag whereContent($value)
 * @method static Builder|Tag whereCreatedAt($value)
 * @method static Builder|Tag whereDescription($value)
 * @method static Builder|Tag whereId($value)
 * @method static Builder|Tag whereTitle($value)
 * @method static Builder|Tag whereUpdatedAt($value)
 * @mixin Query
 */
class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name'];
}
