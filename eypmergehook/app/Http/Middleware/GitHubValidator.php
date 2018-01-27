<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
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
    # This hash signature is passed along with each request in the headers as X-Hub-Signature
    #signature = 'sha1=' + OpenSSL::HMAC.hexdigest(OpenSSL::Digest.new('sha1'), ENV['SECRET_TOKEN'], payload_body)

    if(!isset($_SERVER['HTTP_X_HUB_SIGNATURE']))
    {
      return response()->json(['penis' => '8=D']);
    }

    if($_SERVER['HTTP_X_HUB_SIGNATURE'] != '123456')
    {
      $signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];
      Log::info($signature);
      # sha1=3c73064d4c73156f9d212a3bdf8c343524538806
      return response()->json(['penis' => '8==D']);
    }

    return $next($request);
  }
}
