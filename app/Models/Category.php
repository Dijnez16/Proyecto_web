<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    // Relación con inventario
    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }

    // Contar total de equipos en esta categoría
    public function getInventoryCountAttribute()
    {
        return $this->inventory()->count();
    }

    // Contar equipos asignados en esta categoría
    public function getAssignedCountAttribute()
    {
        return $this->inventory()->whereHas('assignments', function ($query) {
            $query->where('status', 'active');
        })->count();
    }

    // Contar equipos disponibles en esta categoría
    public function getAvailableCountAttribute()
    {
        return $this->inventory()->where('status', 'inventory')->count();
    }
}