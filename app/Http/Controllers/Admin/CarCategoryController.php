<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarCategory;
use Illuminate\Http\Request;

class CarCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $categories = CarCategory::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->paginate(20);

        $stats = [
            'total' => CarCategory::count(),
        ];

        return view('admin.car-categories.index', compact('categories', 'search', 'stats'));
    }

    public function create()
    {
        return view('admin.car-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:car_categories|max:255',
        ]);

        CarCategory::create($request->all());

        return redirect()->route('admin.car-categories.index')
            ->with('success', 'تمت إضافة الفئة بنجاح.');
    }

    public function show($id)
    {
        $category = CarCategory::withCount('cars')->findOrFail($id);
        return view('admin.car-categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = CarCategory::findOrFail($id);
        return view('admin.car-categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:car_categories,name,' . $id . '|max:255',
        ]);

        $category = CarCategory::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('admin.car-categories.index')
            ->with('success', 'تم تحديث الفئة بنجاح.');
    }

    public function destroy($id)
    {
        $category = CarCategory::findOrFail($id);
        
        // التحقق من وجود سيارات مرتبطة
        if ($category->cars()->count() > 0) {
            return redirect()->route('admin.car-categories.index')
                ->with('error', 'لا يمكن حذف الفئة لأن لها سيارات مرتبطة.');
        }

        $category->delete();

        return redirect()->route('admin.car-categories.index')
            ->with('success', 'تم حذف الفئة بنجاح.');
    }
}