<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class VerifyToken
{
    public function handle($request, Closure $next)
    {
        // $token = $request->query('token');
        // dd($token);

        $userId = $request->id;
        
        if (!$userId) {
            return redirect('http://127.0.0.1:8000/login');
        }

        // Panggil API Auth Service untuk mendapatkan data pengguna tanpa header
        $response = Http::get('http://127.0.0.1:8000/api/user/' . $userId);


        // Periksa status response
        if ($response->status() !== 200) {
            return redirect('http://127.0.0.1:8000/login');
        }

        // Ambil data pengguna dari response
        $user = $response->json();
        $token = $user['token'] ?? null;

        if (!$token) {
            return redirect('http://127.0.0.1:8000/login');
        }

        $response = Http::withHeaders([
            'Authorization' => "Bearer $token",
        ])->get('http://127.0.0.1:8000/api/verify-token');

        if ($response->status() !== 200) {
            return redirect('http://127.0.0.1:8000/login');
        }

        $request->merge([
            'username' => $user['username'],
            'email' => $user['email'],
        ]);

        return $next($request);
    }
}

