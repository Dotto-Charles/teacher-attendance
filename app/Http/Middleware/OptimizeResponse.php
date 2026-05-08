<?php
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class OptimizeResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
 
        // Cache static pages for 5 minutes
        if ($request->isMethod('GET') && $response->isSuccessful()) {
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
        }
 
        return $response;
    }
}
