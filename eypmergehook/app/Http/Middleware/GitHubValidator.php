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

    # This hash signature is passed along with each request in the headers as X-Hub-Signature
    if(!$this->isPOST($request))
    {
      return response()->json(['penis' => '8=D']);
    }

    if(!isset($_SERVER['HTTP_X_HUB_SIGNATURE']))
    {
      // return $next($request);
      return response()->json(['penis' => '8==D']);
    }

    $req_signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];
    Log::info($req_signature);
    # sha1=3c73064d4c73156f9d212a3bdf8c343524538806

    $signature = sha1(config('githubsecret.secret'));
    # signature = 'sha1=' + sha1(ENV['SECRET_TOKEN']+payload_body)
    Log::info($signature);

    $signature = sha1(config('githubsecret.secret')+$json_input+'\n');
    Log::info($signature);
    Log::info($json_input);

    if($signature != '123456')
    {
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
