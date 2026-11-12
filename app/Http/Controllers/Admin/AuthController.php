<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman Login Admin (/admin/login).
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Proses autentikasi Admin.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()
                ->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang kembali di Ruang Administrasi Yuk Keliling Sentul.');
        }

        return back()
            ->withErrors(['email' => 'Email atau kata sandi Admin tidak sesuai.'])
            ->onlyInput('email');
    }

    /**
     * Keluar dari sesi Admin.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Anda telah keluar dari Ruang Administrasi.');
    }
}
