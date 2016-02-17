<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->check()) {
            //認証済み
            /*
             * mobile ? return JSON : return redirect /event
             */
            if ($request->has('mobile')) {
                $user = $request->user();
                $user['X-CSRF-TOKEN'] = csrf_token();
                return compact('user');
            }
            return redirect()->route('web_home');
        }

        //未認証, next
        return $next($request);
    }
}
