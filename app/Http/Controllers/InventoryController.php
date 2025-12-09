<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
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

    // Generar código QR para equipo
    public function generateQR(Inventory $inventory)
    {
        $this->generateQRCode($inventory);
        return back()->with('success', 'Código QR generado correctamente.');
    }

    // MÉTODO QR DEFINITIVO
   private function generateQRCode(Inventory $inventory)
{
    // 1. Preparar datos
    $data = "Sistema CMDB\n" .
            "=============\n" .
            "ID: {$inventory->id}\n" .
            "Nombre: {$inventory->name}\n" .
            "Tipo: {$inventory->type}\n" .
            "Categoría: {$inventory->category->name}\n" .
            "Serie: " . ($inventory->serial_number ?? 'N/A') . "\n" .
            "Fecha Ingreso: " . $inventory->entry_date->format('d/m/Y') . "\n" .
            "Estado: {$inventory->status}";
    
    // 2. Crear directorio
    $directory = storage_path('app/public/qr_codes');
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
    
    // 3. Generar QR (VERSIÓN 6.x)
    $qrCode = QrCode::create($data)
        ->setSize(200)
        ->setMargin(10);
    
    $writer = new PngWriter();
    $result = $writer->write($qrCode);
    
    // 4. Guardar archivo
    $filename = 'qr_' . $inventory->id . '_' . time() . '.png';
    $filepath = $directory . '/' . $filename;
    $result->saveToFile($filepath);
    
    // 5. Guardar referencia en BD
    $relativePath = 'qr_codes/' . $filename;
    $inventory->update(['qr_code' => $relativePath]);
    
    return $relativePath;
}

    // SCOPE PARA FILTROS
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('brand', 'like', '%' . $search . '%')
                    ->orWhere('serial_number', 'like', '%' . $search . '%');
            });
        });

        $query->when($filters['type'] ?? false, function ($query, $type) {
            $query->where('type', $type);
        });

        $query->when($filters['category'] ?? false, function ($query, $category) {
            $query->where('category_id', $category);
        });

        $query->when($filters['status'] ?? false, function ($query, $status) {
            $query->where('status', $status);
        });

        return $query;
    }
}