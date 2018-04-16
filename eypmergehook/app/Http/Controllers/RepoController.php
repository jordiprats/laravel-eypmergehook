<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Platform;
use Auth;
use App\User;
use App\Organization;
use App\Repo;

class RepoController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
  }

  public function getUserRepo($nickname, $repo)
  {
    if(User::where('nickname', $nickname)->count() == 1)
    {
      $user = User::where('nickname', $nickname)->first();
      $repo = Repo::where('user_id', $user->id)
          ->where('repo_name', $repo)->first();
      return view('repos.show')->with('repo', $repo)->with('user', $user)->with('releases',$repo->reporeleases);
    }
    else
    {
      if(Organization::where('nickname', $nickname)->count() == 1)
      {
        $organization=Organization::where('nickname', $nickname)->first();
        $repo = Repo::where('organization_id', $organization->id)
            ->where('repo_name', $repo)->first();
        return view('repos.show')->with('repo', $repo)->with('user', $organization)->with('releases',$repo->reporeleases);
      }
      else
      {
        abort(404);
      }
    }
  }
}
