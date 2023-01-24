<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable {
    use HasApiTokens;


    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    protected $hidden = [
        'remember_token',
        'password'
    ];

    public function articles() {
        return $this->hasMany(Article::class);
    }
}
