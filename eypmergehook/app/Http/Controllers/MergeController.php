<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class MergeController extends Controller
{
  public function status()
  {
    return [ 'message' => 'webhook listening' ];
  }

  public function mergeHook(Request $request)
  {
    $json_input=$request->getContent();
    Log::info($json_input);
    try
    {
      Log::info("job Tagger");
      dispatch(new Tagger($repo));
    }
    catch(\Exception $e){
    }

    return [ 'penis'=> '8====D' ];
  }
}
