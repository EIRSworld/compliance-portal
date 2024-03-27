<?php

namespace App\Providers;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class SAML2ServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen('Aacotroneo\Saml2\Events\Saml2LoginEvent', function (Saml2LoginEvent $event) {
//            $messageId = $event->getSaml2Auth()->getLastMessageId();
            // Add your own code preventing reuse of a $messageId to stop replay attacks

            $user = $event->getSaml2User();
//            $userData = [
//                'id' => $user->getUserId(),
//                'attributes' => $user->getAttributes(),
//                'assertion' => $user->getRawSamlAssertion()
//            ];
//            dd($userData);
            $email = $user->getAttribute('http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress');
            $name = $user->getAttribute('http://schemas.microsoft.com/identity/claims/displayname');
            $inputs = [
                'sso_user_id'  => $user->getUserId(),
                'email'        => $email[0] ?? "",
                'name' => $name[0] ?? "",
                'password' => Hash::make(Str::random(10))
            ];
//            Log::info('Authentication Returned frmo MS => ', $inputs);

            $userFromApp = User::where('sso_user_id', $inputs['sso_user_id'])
                ->orWhere('email', $inputs['email'])->first();
            if(!$userFromApp){
//                $newUserFromSso = User::create($inputs);
//                return
                Log::warning('Authentication needed for the user '.$inputs['email']);
                Notification::make()
                    ->title('Please Contact Administrator for Access')
                    ->danger()
                    ->persistent()
                    ->send();
//                Auth::guard('web')->login($newUserFromSso);
            }else{
//                Log::info('Authentication successful for the user '.$inputs['email']);
                Auth::guard('web')->login($userFromApp);
            }
        });

        Event::listen('Aacotroneo\Saml2\Events\Saml2LogoutEvent', function ($event) {
            Auth::logout();
        });
    }
}
