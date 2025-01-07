<?php

namespace App\Providers;

use App\Models\Ticket;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verify Your Email Address')
                ->line('Please verify your email by clicking on the link below:')
                ->action('Confirm My Account', $url)
                ->line('If you did not create an account, no further action is required.');
        });
        View::share('openTicketsCount', Ticket::whereNull('parent_id')->where('status', 'open')->count());
    }
}
