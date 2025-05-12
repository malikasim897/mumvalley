<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;
use App\Models\User;

class BreadcrumbsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the current route name
        $currentRouteName = Route::currentRouteName();

        // Define breadcrumbs based on route names
        $breadcrumbs = [];

        if ($currentRouteName == 'dashboard') {
            $breadcrumbs[] = ['text' => 'Home', 'url' => route('dashboard')];
        } elseif ($currentRouteName == 'user.index') {

            $breadcrumbs[] = ['text' => 'Home', 'url' => route('dashboard')];
            $breadcrumbs[] = ['text' => 'User', 'url' => route('user.index')];

        } elseif ($currentRouteName == 'user.edit') {

            $userId = $request->route('user');

            $breadcrumbs[] = ['text' => 'Home', 'url' => route('dashboard')];
            $breadcrumbs[] = ['text' => 'Users', 'url' => route('user.index')];
            $breadcrumbs[] = ['text' => 'User Profile Settings', 'url' => route('user.edit' ,$userId)];

        } elseif ($currentRouteName == 'profile.edit') {

            $breadcrumbs[] = ['text' => 'Home', 'url' => route('dashboard')];
            $breadcrumbs[] = ['text' => 'Account Settings', 'url' => route('profile.edit')];
            $breadcrumbs[] = ['text' => 'Account', 'url' => route('profile.edit')];
        }



        // Share breadcrumbs data with all views
        view()->share('breadcrumbs', $breadcrumbs);

        return $next($request);

    }
}
