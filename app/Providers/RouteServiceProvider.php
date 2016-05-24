<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Stripe\Customer;
use Stripe\Stripe;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        Route::bind('items', function($id)
        {
            return Item::forCurrentUser()->findOrFail($id);
        });

        Route::bind('categories', function($id)
        {
            return Category::forCurrentUser()->findOrFail($id);
        });

        Route::bind('customers', function($stripeId)
        {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            return Customer::retrieve($stripeId);
        });
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
