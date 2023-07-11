<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryMeal extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    // /**
    //  * Indicates if the IDs are auto-incrementing.
    //  *
    //  * @var bool
    //  */
    // public $incrementing = true;

    protected $fillable = [
        'category_id',
        'meal_id',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function meals(): BelongsToMany
    {
        return $this->belongsToMany(Meal::class);
    }
}
