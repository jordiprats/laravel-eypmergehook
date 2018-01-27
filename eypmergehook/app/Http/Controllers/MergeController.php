<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MergeController extends Controller
{
  public function status()
  {
    return [ 'message' => 'webhook listening' ];
  }

  public function mergeHook(Request $request)
  {
    # This hash signature is passed along with each request in the headers as X-Hub-Signature
    if($request.hasHeader("X-Hub-Signature"))
    {
      $signature=header("X-Hub-Signature", string);
      Log::info("signature: " + $signature);
      Log::info($request->json()->all());
      #signature = 'sha1=' + OpenSSL::HMAC.hexdigest(OpenSSL::Digest.new('sha1'), ENV['SECRET_TOKEN'], payload_body)

      return [ 'penis'=> '8==D' ];
    }
    return [ 'penis'=> '8==D' ];

    return [ 'penis'=> '8==D' ];
  }
}
