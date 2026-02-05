<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });


        View::composer('admin.partials.navbar', function ($view) {
            $user = Auth::user();
            $unreadMessagesCount = 0;
            $unreadNotificationsCount = 0;
            $cartCount = 0;

            if ($user) {
                $unreadMessagesCount = $user->unreadMessages()->count();
                $unreadNotificationsCount = $user->unreadNotifications()->count();

                if ($user->hasRole('client')) {
                    $cartCount = $user->cartItems()->count();
                }
            }

            $view->with(compact('unreadMessagesCount', 'unreadNotificationsCount', 'cartCount'));
        });
    }
}
