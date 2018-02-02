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
    Log::info("mergehook");

    $repo=$request->input('repository.name');
    $fork=$request->input('repository.fork');

    $username_repo=explode("/", $request->input('repository.full_name'));
    $username=$username_repo[0];
    // Log::info($username);
    // Log::info($repo);

    if(!$fork)
    {
      try
      {
        Log::info("job Tagger for ".$username."/".$repo);
        dispatch(new Tagger($username,$repo));
      }
      catch(\Exception $e)
      {
        Log::info("-_(._.)_-");
        Log::info($e);
      }
    }
    else
    {
      Log::info("discarted fork ".$username."/".$repo_name);
    }

    return [ 'penis'=> '8====D' ];
  }
}
