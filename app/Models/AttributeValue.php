<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\Jalalian;

class AttributeValue extends Model {
    use SoftDeletes;

    protected $fillable = [
        'value',
        'attribute_id',
    ];

    public function attribute() {
        return $this->belongsTo(Attribute::class);
    }

    protected function createdAt(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return \Illuminate\Database\Eloquent\Casts\Attribute::get(function ($created_at) {
            return Jalalian::forge($created_at)->format('Y-m-d');
        });
    }
}
