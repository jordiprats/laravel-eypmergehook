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
    Log::info($request->json()->all());
    return [ 'penis'=> 1 ];
  }
}
