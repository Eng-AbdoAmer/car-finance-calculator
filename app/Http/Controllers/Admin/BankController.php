<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index(Request $request)
    {
        // البحث
        $search = $request->get('search');
        $banks = Bank::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('id', 'desc')
        ->paginate(10);

        // إحصائيات
        $stats = [
            'total' => Bank::count(),
        ];

        return view('admin.banks.index', compact('banks', 'search', 'stats'));
    }

    public function create()
    {
        return view('admin.banks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:banks|max:255',
        ]);

        Bank::create($request->all());

        return redirect()->route('admin.banks.index')
            ->with('success', 'تمت إضافة البنك بنجاح.');
    }

    public function show($id)
    {
        $bank = Bank::findOrFail($id);
        return view('admin.banks.show', compact('bank'));
    }

    public function edit($id)
    {
        $bank = Bank::findOrFail($id);
        return view('admin.banks.edit', compact('bank'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:banks,name,' . $id . '|max:255',
        ]);

        $bank = Bank::findOrFail($id);
        $bank->update($request->all());

        return redirect()->route('admin.banks.index')
            ->with('success', 'تم تحديث البنك بنجاح.');
    }

    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();

        return redirect()->route('admin.banks.index')
            ->with('success', 'تم حذف البنك بنجاح.');
    }
}