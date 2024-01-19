<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Contracts\LogoutResponse;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Support\Facades\Crypt;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //Redirect to register after logout
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse
        {
            public function toResponse($request)
            {
                return redirect(route('login'));
            }
        });

        //Check username mail in db for login
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::get()->where('email', $request->email)->first();
            $password = bcrypt($request->password);
            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

    }
}