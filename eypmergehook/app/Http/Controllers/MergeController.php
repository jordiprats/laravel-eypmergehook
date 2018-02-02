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
    // Log::info($request->getContent());

    $repo=$request->input('repository.name');
    $fork=$request->input('repository.fork');

    $repo_name=explode("/", $request->input('repository.full_name'));
    $username=$repo_name[0];
    // Log::info($username);
    // Log::info($repo);

    if(!$fork)
    {
      try
      {
        Log::info("job Tagger for ".$username."/".$repo_name);
        dispatch(new Tagger($username,$repo));
      }
      catch(\Exception $e){
      }
    }
    else
    {
      Log::info("discarted fork ".$username."/".$repo_name);
    }

    return [ 'penis'=> '8====D' ];
  }
}
