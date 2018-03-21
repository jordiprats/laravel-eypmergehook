<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\Organization;
use App\Repo;
use App\LinkedSocialAccount;
use Carbon\Carbon;
use GitHub;
use Github\ResultPager;

class OrganizationController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public static function fetchGitHubRepos($organization, $github)
  {
    $github_paginator  = new ResultPager($github);

    foreach ($github_paginator->fetchAll($github->users(), 'repositories', [$organization->nickname]) as $github_repo)
    {
      $repo = Repo::where(['clone_url' => $github_repo['clone_url']])->first();

      if(!$repo)
      {
        if($github_repo['fork'])
        {
          // print_r($github_repo);
          #$repo = $client->api('repo')->showById(123456)
          $github_repo_extended=$github->repos()->showById($github_repo['id']);
          // print_r($github_repo_extended);

          $fork=$github_repo_extended['parent']['clone_url'];
        }
        else
        {
          $fork=NULL;
        }

        $is_private=$github_repo['private']?true:false;

        $repo = Repo::create([
            'repo_name'        => $github_repo['name'],
            'full_name'        => $github_repo['full_name'],
            'fork'             => $fork,
            'private'          => $is_private,
            'clone_url'        => $github_repo['clone_url'],
            'organization_id'  => $organization->id,
            'github_id'        => $github_repo['id'],
        ]);
      }
      else
      {
        // TODO: fer update de repo existent
      }
    }
  }
}
