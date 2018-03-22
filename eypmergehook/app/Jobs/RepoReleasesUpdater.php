<?php

namespace App\Jobs;

use GitHub;
use Github\ResultPager;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GrahamCampbell\GitHub\Authenticators\AuthenticatorFactory;
use GrahamCampbell\GitHub\GitHubFactory;
use App\User;
use App\Repo;
use App\Organization;
use App\LinkedSocialAccount;
use Carbon\Carbon;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrganizationController;


class RepoReleasesUpdater implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $username;
  protected $owner;
  protected $repo;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($username, $owner, $repo)
  {
    $this->username = $username;
    $this->owner = $owner;
    $this->repo = $repo;
  }

  /**
   * is a given tag released?
   *
   * @param $github_repo_releases
   * @param $tag_name
   * @return bool
   */
  protected function isReleased($github_repo_releases, $tag_name)
  {
    foreach ($github_repo_releases as $release)
    {
      if($release['name']==$tag_name)
        return true;
    }
    return false;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    Log::info("RepoReleasesUpdater: (".$this->username.") - ".$this->owner."/".$this->repo);
    $user = User::where(['nickname' => $this->username])->first();

    $repo = Repo::where(['full_name' => $this->owner."/".$this->repo])->first();

    if($user)
    {
      if($repo)
      {
        if($repo->autoreleasetags)
        {
          //moure tot lo següent aqui
        }
        $github_account=LinkedSocialAccount::where(['user_id' => $user->id, 'provider' => 'github'])->first();
        if($github_account)
        {
          //TODO: establir limit requests a la api de github
          $github = app('github.factory')->make(['token' => $github_account->token, 'method' => 'token']);


          $github_paginator_releases  = new ResultPager($github);
          $github_repo_releases = $github_paginator->fetchAll($github->repos()->releases(), 'all', [$this->owner, $this->repo]);

          print_r($github_repo_releases);

          $github_paginator  = new ResultPager($github);
          foreach ($github_paginator->fetchAll($github->repos(), 'tags', [$this->owner, $this->repo]) as $github_tag)
          {
            //print_r($github_tag);
            //Log::info($this->owner."/".$this->repo.": ".$github_tag['name']);

            if(!$repo->reporeleases->contains('release_name', $github_tag['name']))
            {
              $reporelease = RepoRelease::create([
              'release_name' => $github_tag['name']
              'repo_id'      => $repo->id,
              ]);
            }

            // miro si existeix a github la release
            if(!$this->isReleased($github_repo_releases, $github_tag['name']))
            {
              $github->repos()->releases()->create($this->owner, $this->repo, array('tag_name' => $github_tag['name'], 'name' => $github_tag['name'], 'body' => $github_tag['name'], 'target_commitish' => 'master'));
            }
          }
        }
        //Fi coses a moure
      }
      else
        Log::info("RepoReleasesUpdater: repo(".$this->owner."/".$this->repo.") - not found");
    }
    else
      Log::info("RepoReleasesUpdater: user(".$this->username.") - not found");
  }
}
