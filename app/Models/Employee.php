<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'location',
        'photo_path',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    // Relación con asignaciones
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    // Asignaciones actuales activas
    public function currentAssignments()
    {
        return $this->hasMany(Assignment::class)->where('status', 'active');
    }

    // Relación con necesidades/solicitudes
    public function needs()
    {
        return $this->hasMany(Need::class);
    }

    // Obtener nombre completo
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Obtener URL de la foto
    public function getPhotoUrlAttribute()
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : asset('img/default-avatar.png');
    }

    // Obtener equipos asignados actualmente
    public function getAssignedEquipmentAttribute()
    {
        return $this->currentAssignments->load('inventory');
    }
}