<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserAdminController extends Controller
{
    /**
     * عرض قائمة المستخدمين
     */
    public function index(Request $request)
    {
        // بناء الاستعلام مع العلاقات إذا كانت موجودة
        $query = User::query();
        
        // التصفية حسب النوع
        if ($request->filled('type') && in_array($request->type, ['user', 'admin'])) {
            $query->where('type', $request->type);
        }
        
        // البحث بالاسم أو رقم الهاتف
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // الترتيب
        $query->orderBy('created_at', 'desc');
        
        $users = $query->paginate(20);
        
        // إحصائيات
        $stats = [
            'total' => User::count(),
            'admins' => User::where('type', 'admin')->count(),
            'users' => User::where('type', 'user')->count(),
            'active_today' => User::whereDate('created_at', today())->count(),
        ];
        
        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * عرض نموذج إنشاء مستخدم جديد
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * حفظ المستخدم الجديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'type' => 'required|in:user,admin',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'type' => $validated['type'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح.');
    }

    /**
     * عرض تفاصيل مستخدم
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        // يمكن إضافة إحصائيات المستخدم هنا إذا كانت موجودة
        $userStats = [
            'calculations_count' => $user->financeCalculations()->count(),
            'last_calculation' => $user->financeCalculations()->latest()->first(),
        ];
        
        return view('admin.users.show', compact('user', 'userStats'));
    }

    /**
     * عرض نموذج تعديل المستخدم
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * تحديث بيانات المستخدم
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users')->ignore($user->id),
            ],
            'type' => 'required|in:user,admin',
        ]);

        // إذا تم إدخال كلمة مرور جديدة
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح.');
    }

    /**
     * حذف مستخدم
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // منع المستخدم من حذف نفسه
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'لا يمكنك حذف حسابك الخاص.');
        }
        
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم بنجاح.');
    }

    /**
     * تحديث نوع المستخدم (صلاحية)
     */
    public function updateType(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // منع المستخدم من تغيير صلاحيات نفسه
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'لا يمكنك تغيير صلاحيات حسابك الخاص.');
        }
        
        $request->validate([
            'type' => 'required|in:user,admin',
        ]);
        
        $user->update(['type' => $request->type]);
        
        return redirect()->back()
            ->with('success', 'تم تحديث صلاحية المستخدم بنجاح.');
    }
}