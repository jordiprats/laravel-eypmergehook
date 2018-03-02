<?php

namespace App\Jobs;

use GitHub;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GrahamCampbell\GitHub\Authenticators\AuthenticatorFactory;
use GrahamCampbell\GitHub\GitHubFactory;
use App\User;
use App\LinkedSocialAccount;


class GitHubGetUserRepos implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $username;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($username)
  {
    $this->username = $username;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $user = User::where(['nickname' => $this->username])->first();

    if($user)
    {
      $github_account=LinkedSocialAccount::where(['user_id' => $user->id, 'provider' => 'github'])->first();
      if($github_account)
      {
        // echo "token: ".$github_account->token."\n";
        //app(GitHubFactory::class)->make(['token' => $github_account->token, 'method' => 'token', 'cache' => true]);
        $github = app('github.factory')->make(['token' => $github_account->token, 'method' => 'token']);
        //$repos = GitHub::connection()->users()->repositories($user->nickname);

        // $repos = $github->users()->repositories($user->nickname);
        // echo count($repos);

        $github_paginator  = new \GitHub\ResultPager($github);


        foreach ($github_paginator->fetchAll($github->users(), 'repositories', ['all']) as $repo)
        {
          echo $repo[full_name]."\n";
        }
        # $repos = $client->api('user')->repositories('KnpLabs');
        # $issue = $client->api('issue')->show('KnpLabs', 'php-github-api', 1);
        // GitHub::connection('main')->issues()->show('GrahamCampbell', 'Laravel-GitHub', 2);
        // GitHub::issues()->show('GrahamCampbell', 'Laravel-GitHub', 2);
        // GitHub::connection()->issues()->show('GrahamCampbell', 'Laravel-GitHub', 2);
      }
    }
  }
}
