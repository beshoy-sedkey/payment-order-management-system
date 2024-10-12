<?php

namespace App\Providers;

use App\Exceptions\ApiHandler;
use Illuminate\Support\Facades\App;
use App\Http\Responses\ApiResponder;
use Illuminate\Support\ServiceProvider;
use App\Http\Responses\ResponsesInterface;
use Illuminate\Contracts\Debug\ExceptionHandler;

class APIServiceProvider extends ServiceProvider
{
    public const ItemsPerPage = 25;
    /**
     * Register services.
     */
    public function register(): void
    {


        // Use the ApiResponder as the concrete implementation for the ResponsesInterface
        $this->app->bind(ResponsesInterface::class, ApiResponder::class);
        if (request()->ajax() || request()->expectsJson() || request()->isJson() || request()->is('api/*')) {
            // Use the ApiHandler as the main exception handler
            $this->app->singleton(ExceptionHandler::class, ApiHandler::class);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (request()->is('api/*')) {
            $language = !in_array(request()->header('X-localization'), ['en', 'ar'])
                ? 'en'
                : request()->header('X-localization');

            // Set Localization
            App::setLocale($language);
        }
    }
}
