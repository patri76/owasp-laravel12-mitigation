<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable (SECURE)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'salt',
    ];

    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin(){
        return $this->is_admin == 1;
    }

    public function articles(){
        return $this->hasMany(Article::class);
    }
}
