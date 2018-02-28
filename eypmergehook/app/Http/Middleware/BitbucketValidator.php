<?php
// https://confluence.atlassian.com/bitbucketserver056/managing-webhooks-in-bitbucket-server-943528831.html

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Request;
use Closure;

class BitbucketValidator
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $json_input=$request->getContent();

    Log::info($json_input);

    if(strlen(config('githubsecret.secret'))<=0)
    {
      Log::info("no esta configurat el githubsecret");
      return response(['penis' => '8D'], 401);
    }

    if(!$this->isPOST($request))
    {
      Log::info("no es POST");
      return response(['penis' => '8=D'], 401);
    }

    # This hash signature is passed along with each request
    # in the headers as X-Hub-Signature
    if(!isset($_SERVER['HTTP_X_HUB_SIGNATURE']))
    {
      Log::info("header no present");
      return response(['penis' => '8==D'], 401);
    }

    // The default for this algorithm is HMACSha256. The header X-Hub-Signature is defined and contains the HMAC.
    $req_signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];
    $signature = 'sha256=' . hash_hmac('sha256', $request->getContent(), config('githubsecret.secret'));

    if($req_signature != $signature)
    {
      Log::info("INVALID SIGNATURE: ".$req_signature." vs ".$signature);
      return response(['penis' => '8===D'], 401);
    }

    Log::info("bitbucket valid");
    return $next($request);
  }

  protected function isPOST($request)
  {
      return in_array($request->method(), ['POST']);
  }
}
