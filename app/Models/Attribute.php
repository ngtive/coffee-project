<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\Jalalian;

class Attribute extends Model {
    use SoftDeletes;

    protected $fillable = ['name', 'is_weight', 'is_color'];


    public function values() {
        return $this->hasMany(AttributeValue::class);
    }

    protected function createdAt(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return \Illuminate\Database\Eloquent\Casts\Attribute::get(function ($created_at) {
            return Jalalian::forge($created_at)->format('Y-m-d');
        });
    }
}
