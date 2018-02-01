<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Jobs\Tagger;

class MergeController extends Controller
{
  public function status()
  {
    return [ 'message' => 'webhook listening' ];
  }

  public function mergeHook(Request $request)
  {
    Log::info($request->getContent());

    $repo=$request->input('repository.name');
    $fork=$request->input('repository.fork');
    $full_name=$request->input('repository.full_name');

    Log::info($repo);
    Log::info(gettype($fork)." ".$fork);
    Log::info($full_name);
    
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
