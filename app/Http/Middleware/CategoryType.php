<?php

namespace App\Http\Middleware;

use Closure;

class CategoryType
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
        $request->category_type = \App\Models\CategoryType::where('slug', $request->slug)->enable()->first();
        return $next($request);
    }
}
