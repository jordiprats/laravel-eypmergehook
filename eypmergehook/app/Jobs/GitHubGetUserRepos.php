<?php

namespace App\Jobs;

use GitHub;
use Github\ResultPager;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GrahamCampbell\GitHub\Authenticators\AuthenticatorFactory;
use GrahamCampbell\GitHub\GitHubFactory;
use App\User;
use App\Repo;
use App\LinkedSocialAccount;
use Carbon\Carbon;


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

    //TODO: moure al UserController
    if($user)
    {
      $github_account=LinkedSocialAccount::where(['user_id' => $user->id, 'provider' => 'github'])->first();
      if($github_account)
      {
        //TODO: establir limit requests a la api de github
        $github = app('github.factory')->make(['token' => $github_account->token, 'method' => 'token']);

        //TODO: afegir minim d'update
        if(!$user->github_organizations_updated_on)
        {
          // $memberships = $client->user()->memberships()->all();
          // $membership = $client->user()->memberships()->organization('KnpLabs');
          $memberships = $github->me()->memberships()->all();
          print_r($memberships);

        }

        //TODO: afegir minim d'update
        if(!$user->github_repos_updated_on)
        {
          // $repos = $github->users()->repositories($user->nickname);
          $github_paginator  = new ResultPager($github);

          foreach ($github_paginator->fetchAll($github->users(), 'repositories', [$user->nickname]) as $github_repo)
          {
            // echo $github_repo['full_name']."\n";

            $repo = Repo::where(['clone_url' => $github_repo['clone_url']])->first();

            if(!$repo)
            {
              if($github_repo['owner']['login']==$user->nickname)
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

                // echo "===\n";
                // echo "name: ".$github_repo['name']."\n";
                // echo "full_name: ".$github_repo['full_name']."\n";
                // echo "fork: ".$fork."\n";
                // echo "private: ".$is_private."\n";
                // echo "clone_url: ".$github_repo['clone_url']."\n";
                // echo "user_id: ".$user->id."\n";
                // echo "github_id: ".$github_repo['id']."\n";

                $repo = Repo::create([
                    'repo_name'        => $github_repo['name'],
                    'full_name'        => $github_repo['full_name'],
                    'fork'             => $fork,
                    'private'          => $is_private,
                    'clone_url'        => $github_repo['clone_url'],
                    'user_id'          => $user->id,
                    'github_id'        => $github_repo['id'],
                ]);
              }
              else
              {
                // TODO: check for organitzation membership
              }
            }
            else
            {
              // TODO: fer update de repo existent
            }
          }

          $user->github_repos_updated_on = \Carbon::now();

          $user->save();
        }



      }
    }
  }
}
