<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FuelType;
use Illuminate\Http\Request;

class FuelTypeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $types = FuelType::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->paginate(20);

        $stats = [
            'total' => FuelType::count(),
        ];

        return view('admin.fuel-types.index', compact('types', 'search', 'stats'));
    }

    public function create()
    {
        return view('admin.fuel-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:fuel_types|max:255',
        ]);

        FuelType::create($request->all());

        return redirect()->route('admin.fuel-types.index')
            ->with('success', 'تمت إضافة نوع الوقود بنجاح.');
    }

    public function show($id)
    {
        $type = FuelType::withCount('cars')->findOrFail($id);
        return view('admin.fuel-types.show', compact('type'));
    }

    public function edit($id)
    {
        $type = FuelType::findOrFail($id);
        return view('admin.fuel-types.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:fuel_types,name,' . $id . '|max:255',
        ]);

        $type = FuelType::findOrFail($id);
        $type->update($request->all());

        return redirect()->route('admin.fuel-types.index')
            ->with('success', 'تم تحديث نوع الوقود بنجاح.');
    }

    public function destroy($id)
    {
        $type = FuelType::findOrFail($id);
        
        // التحقق من وجود سيارات مرتبطة
        if ($type->cars()->count() > 0) {
            return redirect()->route('admin.fuel-types.index')
                ->with('error', 'لا يمكن حذف نوع الوقود لأن له سيارات مرتبطة.');
        }

        $type->delete();

        return redirect()->route('admin.fuel-types.index')
            ->with('success', 'تم حذف نوع الوقود بنجاح.');
    }
}