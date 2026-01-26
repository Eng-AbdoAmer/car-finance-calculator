<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        if (!Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'غير مسموح لك بالوصول إلى لوحة التحكم');
        }

        return $next($request);
    }
}