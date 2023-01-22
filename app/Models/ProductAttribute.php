<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class ProductAttribute extends Model {
    use SoftDeletes;

    protected $fillable = [
        'quantity',
        'amount',
        'weight',
        'product_id',
    ];

    protected $appends = [
        'combinations'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues() {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_attribute_value');
    }


    public function combinations(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return \Illuminate\Database\Eloquent\Casts\Attribute::get(function () {
            $collection = new Collection();
            foreach ($this->attributeValues as $attributeValue) {
                $collection->add([$attributeValue->attribute->name => $attributeValue->value]);
            }
            return $collection;
        });
    }
}
