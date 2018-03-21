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
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    Log::info("RepoReleasesUpdater: (".$this->username.") - ".$this->owner."/".$this->repo);
    $user = User::where(['nickname' => $this->username])->first();

    //TODO: moure al UserController
    if($user)
    {
      $github_account=LinkedSocialAccount::where(['user_id' => $user->id, 'provider' => 'github'])->first();
      if($github_account)
      {
        //TODO: establir limit requests a la api de github
        $github = app('github.factory')->make(['token' => $github_account->token, 'method' => 'token']);

        //$tags = $client->api('repo')->tags('twbs', 'bootstrap');
        $github_paginator  = new ResultPager($github);
        foreach ($github_paginator->fetchAll($github->repos(), 'tags', [$this->owner, $this->repo]) as $github_tag)
        {
          Log::info($this->owner."/".$this->repo.": ".$github_tag);
        }
      }
    }
  }
}
