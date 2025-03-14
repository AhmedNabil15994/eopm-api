<?php

namespace Modules\Authentication\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if($request->is('api/*')) {
            $ErrorResponse = [
                'message' =>'Unauthenticated.',
            ];
            abort(response()->json($ErrorResponse, 403));
        }

        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
