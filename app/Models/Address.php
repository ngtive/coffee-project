<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model {

    use SoftDeletes;

    protected $fillable = [
        'mobile',
        'address',
        'receiver',
        'telephone',
        'postal_code',
        'user_id',
        'province_id',
        'city_id',
    ];


    protected $appends = [
        'default'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function province() {
        return $this->belongsTo(Province::class);
    }

    public function city() {
        return $this->belongsTo(City::class);
    }


    protected function default(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return \Illuminate\Database\Eloquent\Casts\Attribute::get(function () {
            return !Address::where('created_at', '>', $this->created_at)->exists();
        });
    }

}
