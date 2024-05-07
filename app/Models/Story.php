<?php

namespace App\Models;

use App\Traits\ModelValidatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Story extends Model
{
    use HasFactory, ModelValidatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'slug', 'status', 'thumb_url'
    ];

    /**
     * Validation rules for the model.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '*' => [
                'name' => 'required|max:255',
                'slug' => 'required|max:255',
                'status' => 'required|integer',
                'thumb_url' => 'required|max:255',
            ],
        ];
    }

    /**
     * Get the categories for the story.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'story_categories');
    }
}
