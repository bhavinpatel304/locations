<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiTokenCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('API-TOKEN');

        if($token != "your_api_token"){
            return response()->json(["error" => 'Unauthorized action.', 'code' => 403])->header("Content-Type", "application/json");
        }
        return $next($request);
    }
}
