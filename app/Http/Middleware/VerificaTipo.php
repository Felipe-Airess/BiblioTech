<?php
// app/Http/Middleware/VerificaTipo.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificaTipo
{
    public function handle(Request $request, Closure $next, ...$tipos)
    {
        $user = Auth::guard('web')->user();

        if (! $user || ! in_array($user->tipo_usuario, $tipos)) {
            abort(403, 'Você não tem permissão para acessar esta área.');
        }

        return $next($request);
    }
}
