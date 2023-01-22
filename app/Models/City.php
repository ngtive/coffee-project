<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model {

    protected $fillable = [
        'shipping_id',
        'name',
        'province_id'
    ];


    public function province() {
        return $this->belongsTo(Province::class);
    }
}
