<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SomenteMembro
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('web')->check()) {
            abort(403, 'Contas administrativas não podem acessar áreas exclusivas de membros.');
        }

        if (! Auth::guard('membro')->check()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Você precisa entrar como membro para acessar esta área.'], 401)
                : redirect()->route('login')->with('erro', 'Entre como membro para acessar essa área.');
        }

        return $next($request);
    }
}
