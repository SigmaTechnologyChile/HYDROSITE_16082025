<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InjectSaldosScript
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Solo aplicar al libro de caja tabular
        if ($request->route() && $request->route()->getName() === 'libro_caja_tabular.show') {
            $content = $response->getContent();
            
            // Buscar si hay un script inyectado en la respuesta
            if (isset($response->original) && is_array($response->original) && isset($response->original['injectedScript'])) {
                $script = $response->original['injectedScript'];
                
                // Inyectar el script antes del cierre del body
                $content = str_replace('</body>', $script . '</body>', $content);
                $response->setContent($content);
            }
        }
        
        return $response;
    }
}
