<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Request;
use Closure;

class GitHubValidator
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

    if(!$this->isPOST($request))
    {
      return response(['penis' => '8=D'], 401);
    }

    # This hash signature is passed along with each request
    # in the headers as X-Hub-Signature
    if(!isset($_SERVER['HTTP_X_HUB_SIGNATURE']))
    {
      return response(['penis' => '8==D'], 401);
    }

    $req_signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];
    // Log::info($req_signature);

    $signature = 'sha1=' . hash_hmac('sha1', $request->getContent(), config('githubsecret.secret'));
    // Log::info($signature);

    if($req_signature != $signature)
    {
      // Log::info("INVALID SIGNATURE");
      return response(['penis' => '8===D'], 401);
    }

    return $next($request);
  }

  protected function isPOST($request)
  {
      return in_array($request->method(), ['POST']);
  }
}
