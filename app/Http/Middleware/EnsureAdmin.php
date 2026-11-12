<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Pastikan hanya Admin yang sudah login yang bisa mengakses halaman /admin/*.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()
                ->route('admin.login')
                ->with('warning', 'Silakan masuk menggunakan akun Admin terlebih dahulu.');
        }

        return $next($request);
    }
}
