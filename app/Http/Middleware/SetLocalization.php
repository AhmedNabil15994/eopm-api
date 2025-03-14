<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocalization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $acceptLanguage = $request->header('Accept-Language');
        $locales = explode(',', $acceptLanguage);
        $validLocale = env('APP_LOCALE'); // Default locale

        foreach ($locales as $locale) {
            $locale = trim(explode(';', $locale)[0]); // Remove quality factor
            if (in_array($locale, config('app.supported_locales'))) { // Check supported locales
                $validLocale = $locale;
                break;
            }
        }

        // Set the application locale
        app()->setLocale($validLocale);
        return $next($request);
    }
}
