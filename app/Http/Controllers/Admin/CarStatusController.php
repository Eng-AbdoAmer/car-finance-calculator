<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarStatus;
use Illuminate\Http\Request;

class CarStatusController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $statuses = CarStatus::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('order')
        ->paginate(20);

        $stats = [
            'total' => CarStatus::count(),
        ];

        return view('admin.car-statuses.index', compact('statuses', 'search', 'stats'));
    }

    public function create()
    {
        return view('admin.car-statuses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:car_statuses|max:255',
            'color' => 'required|string|max:7',
            'order' => 'required|integer|min:0',
        ]);

        CarStatus::create($request->all());

        return redirect()->route('admin.car-statuses.index')
            ->with('success', 'تمت إضافة الحالة بنجاح.');
    }

    public function show($id)
    {
        $status = CarStatus::withCount('cars')->findOrFail($id);
        return view('admin.car-statuses.show', compact('status'));
    }

    public function edit($id)
    {
        $status = CarStatus::findOrFail($id);
        return view('admin.car-statuses.edit', compact('status'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:car_statuses,name,' . $id . '|max:255',
            'color' => 'required|string|max:7',
            'order' => 'required|integer|min:0',
        ]);

        $status = CarStatus::findOrFail($id);
        $status->update($request->all());

        return redirect()->route('admin.car-statuses.index')
            ->with('success', 'تم تحديث الحالة بنجاح.');
    }

    public function destroy($id)
    {
        $status = CarStatus::findOrFail($id);
        
        // التحقق من وجود سيارات مرتبطة
        if ($status->cars()->count() > 0) {
            return redirect()->route('admin.car-statuses.index')
                ->with('error', 'لا يمكن حذف الحالة لأن لها سيارات مرتبطة.');
        }

        $status->delete();

        return redirect()->route('admin.car-statuses.index')
            ->with('success', 'تم حذف الحالة بنجاح.');
    }
}