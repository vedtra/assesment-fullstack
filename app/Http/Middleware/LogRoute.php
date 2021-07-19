<?php

namespace App\Http\Middleware;

use Closure;
use App\Log;
class LogRoute
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
        $response =  $next($request);
        $head = serialize(\Request::header());
        $body = serialize(json_decode(file_get_contents('php://input'), true));
       
        $params = ['ip'=> \Request::ip(),
                   'head' => $head,
                   'body' => $body];
        Log::create($params);
        return $response;
    }
}
