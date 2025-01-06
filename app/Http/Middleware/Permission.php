<?php

namespace App\Http\Middleware;

use App\User;
use App\UserPrivilige;
use Closure;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $url)
    {
        $priv = new UserPrivilige();
        $priv = $priv->get_user_priviliges(auth()->user()->role_id, auth()->id());
        foreach ($priv as $p) {
            if ($p->privilige_url == $url) {
                return $next($request);
            }
        }
        return abort(403, 'Unauthorized action.');
    }
}
