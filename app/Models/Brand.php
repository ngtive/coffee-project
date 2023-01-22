<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model {

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'description',
        'logo',
        'status',
    ];

    public function products() {
        return $this->hasMany(Product::class);
    }

    protected function logo(): Attribute {
        return Attribute::get(function ($logo) {
            return \Storage::disk('public')->url($logo);
        });
    }
}
