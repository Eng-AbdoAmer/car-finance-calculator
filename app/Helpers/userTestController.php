<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;

class UserTestController extends Controller
{
    public function index()
    {
        // جلب جميع المستخدمين مع مقالاتهم
        $users = modelAll(User::class, ['posts']);
        return response()->json($users);
    }

    public function show($id)
    {
        // العثور على مستخدم معين مع مقالاته
        $user = modelFind(User::class, $id, ['posts']);
        if (!$user) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($user);
    }

    public function store(Request $request)
    {
        // إنشاء مستخدم جديد
        $user = modelCreate(User::class, $request->only(['name', 'email', 'password']));
        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        // تحديث مستخدم
        $updated = modelUpdate(User::class, $id, $request->only(['name', 'email']));
        if (!$updated) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($updated);
    }

    public function destroy($id)
    {
        // حذف مستخدم
        $deleted = modelDelete(User::class, $id);
        if (!$deleted) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json(['message' => 'Deleted']);
    }

    public function getActiveUsers()
    {
        // جلب المستخدمين النشطين مع شرط مخصص
        $users = modelWhere(User::class, ['status' => 'active', ['age', '>', 18]], ['posts']);
        return response()->json($users);
    }

    public function paginateUsers()
    {
        // عرض المستخدمين مقسمين على صفحات
        $users = modelPaginate(User::class, 10, ['posts']);
        return response()->json($users);
    }

    public function updateCounters($id)
    {
        // زيادة عدد مرات الدخول مثلاً
        modelIncrement(User::class, $id, 'login_count');
        return response()->json(['message' => 'Incremented']);
    }
}