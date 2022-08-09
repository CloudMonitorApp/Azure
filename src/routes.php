<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use CloudMonitor\Azure\Events\LoginFailed;
use CloudMonitor\Azure\Events\LoginSuccess;
use CloudMonitor\Azure\Exceptions\UserNotFoundException;

Route::prefix(config('azure-ad.routes.perfix'))->group(function() {
    Route::get('signon', function() {
        return Socialite::driver('azure-ad')->redirect();
    })->name('azure-ad.signon');

    Route::get('signin', function() {
        $azureADUser = Socialite::driver('azure-ad')->user()->user;

        preg_match(
            config('azure-ad.id.remote.regex'),
            $azureADUser[config('azure-ad.id.remote.property')],
            $match
        );

        $user = call_user_func_array(
            [
                config('auth.providers.users.model'),
                'where'
            ], [
                config('azure-ad.id.local'),
                config('azure-ad.id.remote.regex')
                    ? $match[config('azure-ad.id.remote.index')]
                    : $azureADUser[config('azure-ad.id.remote.property')]
            ]
        );

        if ($user->count() === 0) {
            Event::dispatch(new LoginFailed());
            throw UserNotFoundException;
            abort(401);
        }

        $user = $user->first();
        Auth::loginUsingId($user->id);
        Event::dispatch(new LoginSuccess($user));

        return redirect()->to(config('azure_ad.redirect'));
    })->name('azure-ad.signin');

    Route::get('logout', function() {
        auth()->logout();
        return redirect()->away('https://login.windows.net/'. config('azure_ad.tenant') .'/oauth2/logout?postlogoutredirect_uri='. request()->root() .'/auth/signout');
    })->name('azure-ad.logout');
});
