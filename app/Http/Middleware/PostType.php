<?php

namespace App\Http\Middleware;

use Closure;

class PostType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->post_type = \App\Models\PostType::where('slug', $request->slug)->enable()->first();
        return $next($request);
    }
}
