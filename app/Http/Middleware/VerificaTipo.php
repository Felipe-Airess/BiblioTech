<?php
// app/Http/Middleware/VerificaTipo.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificaTipo
{
    // Troque o string $tipo por ...$tipos
    public function handle(Request $request, Closure $next, ...$tipos)
    {
        $user = $request->user();

        // Agora verificamos se o tipo do usuário está DENTRO do array de tipos permitidos
        if (! $user || ! in_array($user->tipo_usuario, $tipos)) {
            abort(403);
        }

        return $next($request);
    }
}