<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Visitor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TrackVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only track guests and specific routes
        if (!Auth::check() && $this->shouldTrackRoute($request)) {
            try {
                Visitor::create([
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'page_visited' => $request->path() == '/' ? 'home' : $request->path()
                ]);

                // Log untuk debugging
                Log::info('Visitor tracked', [
                    'ip' => $request->ip(),
                    'path' => $request->path()
                ]);
            } catch (\Exception $e) {
                Log::error('Error tracking visitor: ' . $e->getMessage());
            }
        }

        return $next($request);
    }

    private function shouldTrackRoute(Request $request)
    {
        // Daftar route yang ingin di-track
        $trackableRoutes = [
            '/',
            'news',
            'about',
            'contact',
            // Tambahkan route publik lainnya
        ];

        $path = $request->path();
        return in_array($path, $trackableRoutes) || str_starts_with($path, 'news/');
    }
}
