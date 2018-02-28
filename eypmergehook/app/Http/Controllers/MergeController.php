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

    // github
    $repo=$request->input('repository.name');
    $fork=$request->input('repository.fork');
    $username_repo=explode("/", $request->input('repository.full_name'));
    $username=$username_repo[0];

    // bitbucket
    $project_key=$request->input('repository.project.key');

    // heuristics tipus repo

    if($project_key=="")
    {
      if(!$fork)
      {
        try
        {
          Log::info("job Tagger for ".$username."/".$repo);
          dispatch(new Tagger($username, $repo));
        }
        catch(\Exception $e)
        {
          Log::info("-_(._.)_-");
          Log::info($e);
        }
      }
      else
      {
        Log::info("discarted fork ".$username."/".$repo);
      }
    }
    else
    {
      Log::info("discarting bitbucket repo: ".$project_key."/".$repo);
    }

    // Log::info($username);
    // Log::info($repo);

    return [ 'penis'=> '8====D' ];
  }
}
