<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model {

    protected $fillable = [
        'title',
        'admin_id',
        'slug',
        'content',
        'status',
        'cover',
        'title_en',
    ];

    public function author() {
        return $this->belongsTo(Admin::class);
    }

    protected function cover(): \Illuminate\Database\Eloquent\Casts\Attribute {
        return new \Illuminate\Database\Eloquent\Casts\Attribute(
            get: function ($cover) {
                return $cover ? Storage::disk('public')->url($cover) : null;
            }
        );
    }
}
