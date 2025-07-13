<?php

namespace App\Providers;

use App\Models\User;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Ignorando las rutas predefinidas
        Scramble::ignoreDefaultRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(
                    SecurityScheme::http('bearer')
                );
            });

        // =====================================================================

        // Acceso restringido a la documentación generada por Scramble...

        // Gate::define('viewApiDocs', function (User $user) {
        //     // return in_array($user->email, ['admin@app.com']);
        //     return in_array($user->email, ['scribe@example.com']);
        // });

        // Gate::define('viewApiDocs', function () {
        //     // dd([
        //     //     'provided' => request()->bearerToken(),
        //     //     'expected' => config('scramble.docs_token'),
        //     // ]);
        //     // return request()->bearerToken() === config('scramble.docs_token');


        //     dd(request()->header('Authorization'));


        //     $authorizationHeader = request()->header('Authorization');

        //     // Opcional: puedes loguearlo para confirmar que llega
        //     // \Log::info('Authorization header:', ['value' => $authorizationHeader]);

        //     return $authorizationHeader === 'Bearer ' . config('scramble.docs_token');
        // });

        // Scramble::auth(function () {
        //     return request()->bearerToken() === config('scramble.docs_token');
        // });
    }
}
