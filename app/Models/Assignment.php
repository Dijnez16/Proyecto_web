<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'inventory_id',
        'assignment_date',
        'return_date',
        'return_reason',
        'returned_to_user',
        'status'
    ];

    protected $casts = [
        'assignment_date' => 'date',
        'return_date' => 'date'
    ];

    // Relación con empleado
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Relación con inventario
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    // Relación con usuario que procesó la devolución
    public function returnedByUser()
    {
        return $this->belongsTo(User::class, 'returned_to_user');
    }

    // Verificar si está activa
    public function isActive()
    {
        return $this->status === 'active';
    }

    // Marcar como devuelto
    public function markAsReturned($reason, $userId)
    {
        $this->update([
            'return_date' => now(),
            'return_reason' => $reason,
            'returned_to_user' => $userId,
            'status' => 'returned'
        ]);

        // Actualizar estado del inventario
        $this->inventory->update(['status' => 'technical_review']);
    }
}