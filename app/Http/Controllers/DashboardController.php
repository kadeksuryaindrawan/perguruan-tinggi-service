<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function logout(Request $request,$id)
    {
        $response = Http::post('http://127.0.0.1:8000/api/user/' . $id . '/logout');
        if ($response->status() !== 200) {
            return redirect('/')->withErrors(['message' => 'Failed to log out user in auth service']);
        }

        return redirect('/');
    }
}
