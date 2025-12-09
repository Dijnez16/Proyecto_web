<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'donation_to',
        'responsible_person',
        'donation_date',
        'notes'
    ];

    protected $casts = [
        'donation_date' => 'date'
    ];

    // Relación con inventario
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    // Registrar donación
    public static function registerDonation($inventoryId, $donationTo, $responsiblePerson, $notes = null)
    {
        // Crear registro de donación
        self::create([
            'inventory_id' => $inventoryId,
            'donation_to' => $donationTo,
            'responsible_person' => $responsiblePerson,
            'donation_date' => now(),
            'notes' => $notes
        ]);

        // Actualizar estado del inventario
        Inventory::find($inventoryId)->update(['status' => 'donated']);
    }
}