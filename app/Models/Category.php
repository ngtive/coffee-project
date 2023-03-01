<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Category extends Model {

    use HasRecursiveRelationships;

    protected $fillable = [
        'name',
        'name_en',
        'parent_id',
        'slug',
        'status',
        'cover'
    ];


    protected $with = ['children'];

    protected $appends = [
        'selected'
    ];

    public function products() {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    protected function cover(): Attribute {
        return Attribute::get(function ($cover) {
            if (!$cover) {
                return;
            }
            return Storage::disk('public')->url($cover);
        });
    }

    protected function selected(): Attribute {
        return Attribute::get(function () {
            return false;
        });
    }

}
