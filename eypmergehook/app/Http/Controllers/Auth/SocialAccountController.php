<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\User;
use App\LinkedSocialAccount;

use Socialite;

class SocialAccountController extends Controller
{
  /**
   * Redirect the user to the GitHub authentication page.
   *
   * @return Response
   */
  public function redirectToProvider($provider)
  {
    return \Socialite::driver($provider)->scopes(['read:user', 'public_repo'])->redirect();
  }

  /**
   * Obtain the user information
   *
   * @return Response
   */
  public function handleProviderCallback($provider)
  {
    Log::info($provider);

    $userSocial = Socialite::driver($provider)->user();
    $user = User::where(['email' => $userSocial->getEmail()])->first();

    if($user)
    {
      # usuari ja existent (mail ja esta a la DB)
      auth()->login($user, true);
      // TODO: millorar
      $lsa = LinkedSocialAccount::where(['user_id' => $user->id])->first();
      return redirect()->action('HomeController@index');
    }
    else
    {
      # donar d'alta user si no el tenim per un altre provider
      $user = User::create([
          'email'     => $userSocial->getEmail(),
          'name'      => $userSocial->getName(),
          'nickname'  => $userSocial->getNickname(),
      ]);

      // // OAuth Two Providers
      // $token = $user->token;
      // $refreshToken = $user->refreshToken; // not always provided
      // $expiresIn = $user->expiresIn;
      //
      // // OAuth One Providers
      // $token = $user->token;
      // $tokenSecret = $user->tokenSecret;

      // TODO: gestio OAuth One
      LinkedSocialAccount::create([
        'user_id' => $user->id,
        'provider' => $provider,
        'token' => $userSocial->token,
        'refresh_token' => $userSocial->refreshToken,
        'expires_in' => $userSocial->expiresIn,
      ]);

      auth()->login($user, true);
      return redirect()->action('HomeController@index');
    }
  }
}
