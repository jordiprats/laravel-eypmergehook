<?php

namespace App\Http\Middleware;

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

    if(!isset($_SERVER['HTTP_X_HARDIK']))
    {
      return Response::json(array('error'=-->'Please set custom header'));
    }

    if($_SERVER['HTTP_X_HARDIK'] != '123456')
    {
      return Response::json(array('error'=>'wrong custom header'));
    }

    return $next($request);
  }
}
