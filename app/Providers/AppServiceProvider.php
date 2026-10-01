<?php

namespace App\Providers;

use App\Models\Conference;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
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
        View::composer(
            'layouts.admin',
            function ($view) {

                $activeConference = Conference::with('setting')
                    ->whereHas('setting', function ($query) {
                        $query->where('is_active', true);
                    })
                    ->latest('year')
                    ->first();

                if (!$activeConference) {
                    $activeConference = Conference::latest('year')
                        ->first();
                }

                $view->with(
                    'activeConference',
                    $activeConference
                );
            }
        );

        View::composer(
            'layouts.participant',
            function ($view) {

                $showSubmissionMenu = false;

                if (
                    Auth::check()
                    && Auth::user()->role === 'participant'
                ) {
                    $showSubmissionMenu = Auth::user()
                        ->participants()
                        ->whereHas(
                            'registrationType',
                            function ($query) {
                                $query->where(
                                    'category',
                                    'presenter'
                                );
                            }
                        )
                        ->exists();
                }

                $view->with(
                    'showSubmissionMenu',
                    $showSubmissionMenu
                );
            }
        );

        Paginator::useBootstrapFive();
    }
}
