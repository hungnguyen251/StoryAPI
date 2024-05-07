<?php

namespace App\Models;

use App\Traits\ModelValidatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory, ModelValidatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'slug',
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
                'name' => 'required|max:100',
                'slug' => 'required|max:100',
            ],
        ];
    }

    public function stories(): BelongsToMany
    {
        return $this->belongsToMany(Story::class, 'story_categories');
    }
}
