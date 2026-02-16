<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransmissionType;
use Illuminate\Http\Request;

class TransmissionTypeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $types = TransmissionType::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->paginate(20);

        $stats = [
            'total' => TransmissionType::count(),
        ];

        return view('admin.transmission-types.index', compact('types', 'search', 'stats'));
    }

    public function create()
    {
        return view('admin.transmission-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:transmission_types|max:255',
        ]);

        TransmissionType::create($request->all());

        return redirect()->route('admin.transmission-types.index')
            ->with('success', 'تمت إضافة نوع الجير بنجاح.');
    }

    public function show($id)
    {
        $type = TransmissionType::withCount('cars')->findOrFail($id);
        return view('admin.transmission-types.show', compact('type'));
    }

    public function edit($id)
    {
        $type = TransmissionType::findOrFail($id);
        return view('admin.transmission-types.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:transmission_types,name,' . $id . '|max:255',
        ]);

        $type = TransmissionType::findOrFail($id);
        $type->update($request->all());

        return redirect()->route('admin.transmission-types.index')
            ->with('success', 'تم تحديث نوع الجير بنجاح.');
    }

    public function destroy($id)
    {
        $type = TransmissionType::findOrFail($id);
        
        // التحقق من وجود سيارات مرتبطة
        if ($type->cars()->count() > 0) {
            return redirect()->route('admin.transmission-types.index')
                ->with('error', 'لا يمكن حذف نوع الجير لأن له سيارات مرتبطة.');
        }

        $type->delete();

        return redirect()->route('admin.transmission-types.index')
            ->with('success', 'تم حذف نوع الجير بنجاح.');
    }
}