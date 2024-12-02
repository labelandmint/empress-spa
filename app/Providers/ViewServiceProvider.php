<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('includes.header', function ($view) {
            // Retrieve dynamic data here
            $data = [
                'setting' => \App\Models\Setting::first(),
            ];

            $view->with($data);
        });
    }

    public function register()
    {
        //
    }
}
