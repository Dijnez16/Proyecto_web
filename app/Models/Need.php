<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Need extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'equipment_type',
        'software_name',
        'priority',
        'status',
        'description'
    ];

    // Relación con empleado
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Obtener nombre del equipo/software solicitado
    public function getItemNameAttribute()
    {
        return $this->equipment_type ?: $this->software_name;
    }

    // Obtener clase CSS para prioridad
    public function getPriorityClassAttribute()
    {
        return [
            'low' => 'bg-secondary',
            'medium' => 'bg-warning',
            'high' => 'bg-danger'
        ][$this->priority] ?? 'bg-secondary';
    }

    // Obtener clase CSS para estado
    public function getStatusClassAttribute()
    {
        return [
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger'
        ][$this->status] ?? 'bg-secondary';
    }

    // Aprobar solicitud
    public function approve()
    {
        $this->update(['status' => 'approved']);
    }

    // Rechazar solicitud
    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }
}