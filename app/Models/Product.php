<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\Jalalian;

class Product extends Model {

    use SoftDeletes;


    protected $fillable = [
        'quantity',
        'amount',
        'cover',
        'weight',
        'slug',
        'title',
        'title_en',
        'description',
        'status',
    ];

    protected $with = [
        'discount'
    ];

    protected $appends = [
        'thumbs',
        'has_product_attribute',
        'has_discount',
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function specifications() {
        return $this->hasMany(ProductSpecification::class);
    }

    public function gallery() {
        return $this->hasMany(ProductGallery::class);
    }

    public function productAttributes() {
        return $this->hasMany(ProductAttribute::class);
    }

    public function discount() {
        return $this->hasOne(Discount::class);
    }


    public function thumbs(): Attribute {

        $coverPath = $this->cover;

        return Attribute::get(function () {
            $pathInfo = pathinfo($this->attributes['cover']);

            return [
                '100' =>
                    Storage::disk('public')->url($pathInfo['dirname'] . '/' . '100.' . $pathInfo['extension']),
                '200' =>
                    Storage::disk('public')->url($pathInfo['dirname'] . '/' . '200.' . $pathInfo['extension']),
                '300' =>
                    Storage::disk('public')->url($pathInfo['dirname'] . '/' . '300.' . $pathInfo['extension']),
                '400' =>
                    Storage::disk('public')->url($pathInfo['dirname'] . '/' . '400.' . $pathInfo['extension']),
                '500' =>
                    Storage::disk('public')->url($pathInfo['dirname'] . '/' . '500.' . $pathInfo['extension'])
            ];
        });
    }

    protected function cover(): Attribute {
        return Attribute::get(function ($cover) {
            return Storage::disk('public')->url($cover);
        });
    }

    public function hasProductAttribute(): Attribute {
        return new Attribute(
            get: function () {
                return $this->productAttributes()->exists();
            }
        );
    }

    public function hasDiscount(): Attribute {
        return new Attribute(
            get: function () {
                return !!$this->discount;
            }
        );
    }

    protected function createdAt(): Attribute {
        return Attribute::get(function ($created_at) {
            return Jalalian::forge($created_at)->format('Y-m-d');
        });
    }

}
