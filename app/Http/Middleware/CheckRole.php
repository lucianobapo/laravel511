<?php namespace App\Http\Middleware;

// First copy this file into your middleware directoy

use Closure;

class CheckRole{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Get the required roles from the route
//        dd($request->route()->getAction());
        return $next($request);

        $roles = $this->getRequiredRoleForRoute($request->route());
//        dd($request->user()->hasRole($roles));

        // Check if a role is required for the route, and
        // if so, ensure that the user has that role.
        if($request->user()->hasRole($roles) || !$roles)
        {
            return $next($request);
        }
        flash()->error(trans('app.accessUnauthorized'));
        return redirect('/welcome');

//        return response([
//            'error' => [
//                'code' => 'INSUFFICIENT_ROLE',
//                'description' => 'You are not authorized to access this resource.'
//            ]
//        ], 401);

    }

    private function getRequiredRoleForRoute($route)
    {
        $actions = $route->getAction();
        return isset($actions['roles']) ? $actions['roles'] : null;
    }

}
