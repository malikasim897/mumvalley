<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class Breadcrumbs extends Component
{
    // public $breadcrumbs = [];
    // /**
    //  * Create a new component instance.
    //  */
    // public function __construct()
    // {
    //     $currentRouteName = Route::currentRouteName();

    //     // Define breadcrumb links based on route name
    //     if ($currentRouteName === 'profile') {
    //         $this->breadcrumbs[] = ['route' => 'dashboard', 'text' => 'Dashboard'];
    //         $this->breadcrumbs[] = ['route' => 'profile', 'text' => 'Profile'];
    //     } elseif ($currentRouteName === 'user') {
    //         $this->breadcrumbs[] = ['route' => 'dashboard', 'text' => 'Home'];
    //         $this->breadcrumbs[] = ['route' => 'user.index', 'text' => 'User'];
    //     } elseif ($currentRouteName === 'post') {
    //         $this->breadcrumbs[] = ['route' => 'home', 'text' => 'Home'];
    //         $this->breadcrumbs[] = ['route' => 'blog', 'text' => 'Blog'];
    //         $this->breadcrumbs[] = ['route' => 'post', 'text' => 'Post'];
    //     }
    // }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.breadcrumbs');
    }
}
