<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LoginThrottle
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next)
    {
        $key = $this->throttleKey($request);

        if ($this->limiter->tooManyAttempts($key, 3)) {
            return response()->json([
                'message' => 'Muitas tentativas de login. Tente novamente em ' . $this->limiter->availableIn($key) . ' segundos.'
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        $this->limiter->hit($key, 60);

        $response = $next($request);

        if ($response->status() === 200) {
            $this->limiter->clear($key);
        }

        return $response;
    }

    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }
}
