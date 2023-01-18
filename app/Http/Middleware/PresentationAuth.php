<?php

namespace App\Http\Middleware;

use App\Models\Presentation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresentationAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $presentation = Presentation::where([
            'id' => $request->route()->parameter('presentation'),
            'user_id' => Auth::user()['id']
        ])->first();

        if (!$presentation) {
            return response(['message' => 'Forbidden Access!'], 403);
        }

        return $next($request);
    }
}
