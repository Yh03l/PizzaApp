<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pizza extends Model
{
    protected $fillable = ['name', 'base_price'];

    protected $casts = [
        'base_price' => 'float'
    ];

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'pizza_ingredients')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
