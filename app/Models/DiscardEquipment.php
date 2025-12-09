<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscardEquipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'technical_opinion',
        'discarded_by',
        'discard_date'
    ];

    protected $casts = [
        'discard_date' => 'date'
    ];

    // Relación con inventario
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    // Relación con usuario que descartó
    public function discardedBy()
    {
        return $this->belongsTo(User::class, 'discarded_by');
    }

    // Marcar equipo como descartado
    public static function markAsDiscarded($inventoryId, $opinion, $userId)
    {
        // Crear registro de descarte
        self::create([
            'inventory_id' => $inventoryId,
            'technical_opinion' => $opinion,
            'discarded_by' => $userId,
            'discard_date' => now()
        ]);

        // Actualizar estado del inventario
        Inventory::find($inventoryId)->update(['status' => 'discard']);
    }
}