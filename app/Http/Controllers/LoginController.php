<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('inicioSesion');
    }

    public function login(Request $request)
{
    $usuario = $request->input('usuario');
    $password = $request->input('password');

    $user = Redis::hgetall('user:' . $usuario);

    if ($user && Hash::check($password, $user['password'])) {
        // Establecer una variable de sesión para indicar que el usuario está autenticado
        $request->session()->put('authenticated', true);
        $request->session()->put('user', $usuario);
        return redirect('/home')->with('status', 'Inicio de sesión exitoso!');
    } else {
        return redirect('/login')->with('error', 'Nombre de usuario o contraseña incorrectos.');
    }
}
}