<?php 
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;

class TrustProxies extends Middleware
{
    protected $proxies = '*';  // Trust all proxies

    protected $headers = Request::HEADER_X_FORWARDED_ALL;

    public function handle($request, \Closure $next)
    {
        if ($request->isSecure()) {
            return $next($request);
        }

        return redirect()->secure($request->getRequestUri());
    }
}
