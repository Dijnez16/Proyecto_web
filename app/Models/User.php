<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'name',
        'password',
        'role',
        'active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function isAdmin()
{
    return $this->role === 'admin';
}

    // Verificar si está activo
    public function isActive()
    {
        return $this->active;
    }

    // Relación con asignaciones procesadas
    public function assignmentsProcessed()
    {
        return $this->hasMany(Assignment::class, 'returned_to_user');
    }
}