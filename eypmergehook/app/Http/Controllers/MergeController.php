<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Jobs\Tagger;
use App\Jobs\RepoReleasesUpdater;

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

          $user = User::where(['nickname' => $username])->first();
          if($user)
          {
            dispatch(new RepoReleasesUpdater($user, $username, $repo));
          }
          else
          {
            # check for organization
            $organization = Organization::where(['nickname' => $username])->first();
            if($organization)
            {
              $user=$organization->users()->first();
              if($user)
              {
                dispatch(new RepoReleasesUpdater($user, $username, $repo));
              }
            }
            else
            {
              Log::info("ignorant hook de usuari no registrat");
            }
          }


          //dispatch(new RepoReleasesUpdater(<buscar usuari amb permisos>, $username, $repo));
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

    return [ 'penis'=> '8====D~~' ];
  }
}
