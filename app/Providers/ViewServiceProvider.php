<?php

namespace App\Providers;

use App\Service\TalkroomService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Auth;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // Using closure based composers...
        View::composer(['user.*'], function ($view) {
            $f_user = Auth::user();
            $f_platform = 'user';

            // map location flag
            $f_location = 0;
            if ($f_user) {
                $f_location = TalkroomService::existShareLocationByUser($f_user->id);
            }

            $view->with(compact( 'f_user', 'f_platform', 'f_location'));
            /*$view->with(compact( 'f_user', 'f_platform'));*/
        });

        // Using closure based composers...
        View::composer('admin.*', function ($view) {
            $f_admin = Auth::guard('admin')->user();
            $f_platform = 'admin';
            $view->with(compact( 'f_admin', 'f_platform'));
        });

    }
}
