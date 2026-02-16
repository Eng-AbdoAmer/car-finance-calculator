<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriveType;
use Illuminate\Http\Request;

class DriveTypeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $types = DriveType::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->paginate(20);

        $stats = [
            'total' => DriveType::count(),
        ];

        return view('admin.drive-types.index', compact('types', 'search', 'stats'));
    }

    public function create()
    {
        return view('admin.drive-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:drive_types|max:255',
            'code' => 'required|unique:drive_types|max:10',
        ]);

        DriveType::create($request->all());

        return redirect()->route('admin.drive-types.index')
            ->with('success', 'تمت إضافة نوع الدفع بنجاح.');
    }

    public function show($id)
    {
        $type = DriveType::withCount('cars')->findOrFail($id);
        return view('admin.drive-types.show', compact('type'));
    }

    public function edit($id)
    {
        $type = DriveType::findOrFail($id);
        return view('admin.drive-types.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:drive_types,name,' . $id . '|max:255',
            'code' => 'required|unique:drive_types,code,' . $id . '|max:10',
        ]);

        $type = DriveType::findOrFail($id);
        $type->update($request->all());

        return redirect()->route('admin.drive-types.index')
            ->with('success', 'تم تحديث نوع الدفع بنجاح.');
    }

    public function destroy($id)
    {
        $type = DriveType::findOrFail($id);
        
        // التحقق من وجود سيارات مرتبطة
        if ($type->cars()->count() > 0) {
            return redirect()->route('admin.drive-types.index')
                ->with('error', 'لا يمكن حذف نوع الدفع لأن له سيارات مرتبطة.');
        }

        $type->delete();

        return redirect()->route('admin.drive-types.index')
            ->with('success', 'تم حذف نوع الدفع بنجاح.');
    }
}