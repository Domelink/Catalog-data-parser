<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Products extends Model
{
    use HasUuids;

    protected $table = 'products';

    protected $primaryKey = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'category_uuid',
        'manufacturer',
        'name',
        'code_of_model',
        'description',
        'price',
        'guaranty',
        'availability',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the category that owns the catalog entry.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'category_uuid', 'uuid');
    }
}
