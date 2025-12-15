<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;


class InventoryController extends Controller
{
    // Listar todo el inventario
    public function index()
    {
        $inventory = Inventory::with('category')
            ->latest()
            ->filter(request(['search', 'type', 'category', 'status']))
            ->paginate(10);

        $categories = Category::all();
        
        return view('inventory.index', compact('inventory', 'categories'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $categories = Category::all();
        return view('inventory.create', compact('categories'));
    }

    // Almacenar nuevo equipo
    public function store(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:hardware,software',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100|unique:inventory',
            'cost' => 'nullable|numeric|min:0',
            'entry_date' => 'required|date',
            'depreciation_years' => 'required|integer|min:1|max:10',
            'image' => 'nullable|image|max:2048'
        ]);

        // Procesar imagen si se subió
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('equipment', 'public');
        }

        // Crear equipo
        $inventory = Inventory::create($validated);

        // Generar QR automáticamente
        $this->generateQRCode($inventory);

        return redirect()->route('inventory.index')
            ->with('success', 'Equipo agregado al inventario correctamente.');
    }

    // Mostrar detalles de equipo
    public function show(Inventory $inventory)
    {
        $inventory->load('category', 'assignments.employee');
        return view('inventory.show', compact('inventory'));
    }

    // Mostrar formulario de edición
    public function edit(Inventory $inventory)
    {
        $categories = Category::all();
        return view('inventory.edit', compact('inventory', 'categories'));
    }

    // Actualizar equipo
    public function update(Request $request, Inventory $inventory)
    {
        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:hardware,software',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100|unique:inventory,serial_number,' . $inventory->id,
            'cost' => 'nullable|numeric|min:0',
            'entry_date' => 'required|date',
            'depreciation_years' => 'required|integer|min:1|max:10',
            'image' => 'nullable|image|max:2048'
        ]);

        // Procesar nueva imagen si se subió
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($inventory->image_path) {
                Storage::disk('public')->delete($inventory->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('equipment', 'public');
        }

        // Actualizar equipo
        $inventory->update($validated);

        // Generar QR si no existe
        if (!$inventory->qr_code) {
            $this->generateQRCode($inventory);
        }

        return redirect()->route('inventory.index')
            ->with('success', 'Equipo actualizado correctamente.');
    }

    // Cambiar estado del equipo
    public function updateStatus(Request $request, Inventory $inventory)
    {
        $request->validate([
            'status' => 'required|in:inventory,assigned,discard,donated,technical_review'
        ]);

        $inventory->update(['status' => $request->status]);

        return back()->with('success', 'Estado del equipo actualizado correctamente.');
    }
/*
    // Generar código QR para equipo
    public function generateQR(Inventory $inventory)
    {
        $this->generateQRCode($inventory);
        return back()->with('success', 'Código QR generado correctamente.');
    }
*/
    // MÉTODO QR DEFINITIVO
  public function generateQr(Inventory $inventory)
{
    $url = route('inventory.show', $inventory);

    $result = new Builder(
        writer: new PngWriter(),
        data: $url,
        encoding: new Encoding('UTF-8'),
        size: 250,
        margin: 10
    );

    $qr = $result->build();

    $filename = 'qr_codes/inventory_' . $inventory->id . '.png';

    Storage::disk('public')->put($filename, $qr->getString());

    $inventory->update([
        'qr_code' => $filename
    ]);

    return back()->with('success', 'QR generado correctamente');
}

}