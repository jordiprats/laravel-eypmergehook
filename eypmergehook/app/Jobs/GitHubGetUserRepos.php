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
    Log::info("GitHubGetUserRepos: ".$this->username);

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
          // $memberships = $github->me()->memberships()->all();
          // print_r($memberships);
          foreach ($github->me()->memberships()->all() as $github_membership)
          {
            $organization = Organization::where(['nickname' => $github_membership['organization']['login']])->first();

            if(!$organization)
            {
              // echo "==\n";
              // print_r($github_membership);
              // [login] => whatever
              // [id] => 1234
              // [url] => https://api.github.com/orgs/whatever
              // (...)
              // [avatar_url] => https://avatars3.githubusercontent.com/u/1234?v=4
              // [description] => blabblabla
              $organization = Organization::create([
              'github_id'   => $github_membership['organization']['id'],
              'nickname'    => $github_membership['organization']['login'],
              'url'         => $github_membership['organization']['url']?$github_membership['url']:NULL,
              'avatar_url'  => $github_membership['organization']['avatar_url']?$github_membership['organization']['avatar_url']:NULL,
              'description' => $github_membership['organization']['description']?$github_membership['organization']['description']:NULL,
              ]);
            }
            else
            {
              // TODO: update org
            }

            $organization->save();
            $organization = Organization::where(['nickname' => $github_membership['organization']['login']])->first();

            if($organization)
            {
              if(!$user->organizations()->exists($organization->id))
              {
                Log::info("GitHubGetUserRepos: attaching ".$organization->nickanme." to ".$user->nickname);
                $user->organizations()->attach($organization);
              }
              else {
                Log::info("GitHubGetUserRepos: already attached ".$organization->nickanme." to ".$user->nickname);
              }
            }
            else
            {
              Log::info("GitHubGetUserRepos: org no disponible");
            }



            OrganizationController::fetchGitHubRepos($organization, $github);

            $organization->github_repos_updated_on = Carbon::now();

            $organization->save();
            $user->save();
          }
          $user->github_organizations_updated_on = Carbon::now();
          $user->save();
        }

        //TODO: afegir minim d'update
        if(!$user->github_repos_updated_on)
        {
          UserController::fetchGitHubRepos($user, $github);

          $user->github_repos_updated_on = Carbon::now();
          $user->save();
        }

      }
    }
  }
}
