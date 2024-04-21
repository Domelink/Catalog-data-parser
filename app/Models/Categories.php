<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Categories extends Model
{
    protected $table = 'categories';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'categories',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the catalog entries associated with the category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Products::class, 'category_id', 'id');
    }
}
