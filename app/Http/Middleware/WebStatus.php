<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class WebStatus
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
        if(Config::get('web.web_status')==0){
            return redirect('cjh');
        }
        return $next($request);
    }
}
