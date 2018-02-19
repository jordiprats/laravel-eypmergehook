<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\User;

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
    return \Socialite::driver($provider)->redirect();
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
      auth()->login($user, true);
      return redirect()->action('HomeController@index');
    }
  }
}
