<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    // ESPECIFICAR EL NOMBRE DE LA TABLA - AGREGAR ESTA LÍNEA
    protected $table = 'inventory';

    protected $fillable = [
        'name',
        'type',
        'category_id',
        'brand',
        'serial_number',
        'cost',
        'entry_date',
        'depreciation_years',
        'image_path',
        'status',
        'qr_code'
    ];

    protected $casts = [
        'entry_date' => 'date',
        'cost' => 'decimal:2'
    ];

    // Relación con categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relación con asignaciones
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    // Asignación actual activa
    public function currentAssignment()
    {
        return $this->hasOne(Assignment::class)->where('status', 'active');
    }

    // Obtener fecha de fin de depreciación
    public function getDepreciationEndDateAttribute()
    {
        return $this->entry_date->addYears($this->depreciation_years);
    }

    // Verificar si está próximo a depreciar
    public function isAboutToDepreciate()
    {
        return $this->depreciation_end_date->subMonths(3)->isPast();
    }

    // Obtener URL de la imagen
    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : asset('img/default-equipment.png');
    }

    // Obtener nombre completo del tipo
    public function getTypeNameAttribute()
    {
        return $this->type == 'hardware' ? 'Hardware' : 'Software';
    }
}