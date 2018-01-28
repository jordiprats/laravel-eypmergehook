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
      return response()->json(['penis' => '8=D']);
    }

    # This hash signature is passed along with each request
    # in the headers as X-Hub-Signature
    if(!isset($_SERVER['HTTP_X_HUB_SIGNATURE']))
    {
      // return $next($request);
      return response()->json(['penis' => '8==D']);
    }

    $req_signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];
    Log::info($req_signature);
    # sha1=3c73064d4c73156f9d212a3bdf8c343524538806

    // $signature = sha1(config('githubsecret.secret'));
    // Log::info($signature);

    # signature = 'sha1=' + sha1(ENV['SECRET_TOKEN']+payload_body)
    $signature = "sha1=".sha1(config('githubsecret.secret').$json_input."\n");
    Log::info($signature);
    // Log::info("json: ".$json_input);
    // Log::info("str sha1: ".config('githubsecret.secret').$json_input);

    $sig_check = 'sha1=' . hash_hmac('sha1', $request->getContent(), config('githubsecret.secret'));
    Log::info($sig_check);

    if($req_signature != $signature)
    {
      Log::info("INVALID SIGNATURE");
      // return response()->json(['penis' => '8===D']);
      return $next($request);
    }

    return $next($request);
  }

  protected function isPOST($request)
  {
      return in_array($request->method(), ['POST']);
  }
}
