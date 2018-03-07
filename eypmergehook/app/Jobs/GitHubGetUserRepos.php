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
        $github = app('github.factory')->make(['token' => $github_account->token, 'method' => 'token']);

        $github_paginator  = new ResultPager($github);

        foreach ($github_paginator->fetchAll($github->users(), 'repositories', [$user->nickname]) as $github_repo)
        {
          echo $github_repo['full_name']."\n";

          $repo = Repo::where(['clone_url' => $github_repo['clone_url']])->first();

          // $table->string('repo_name');
          // $table->string('full_name')->nullable();
          // $table->string('project_name')->nullable();
          // $table->boolean('fork');
          // $table->boolean('private');
          // $table->string('clone_url');
          // $table->integer('user_id')->nullable()->references('id')->on('users');
          // $table->integer('organization_id')->nullable()->references('id')->on('organizations');
          // $table->boolean('telegram_notifications')->default(true);
          // $table->string('telegram_chatid')->nullable();
          if(!$repo)
          {
            if($github_repo['owner']['login']==$user->nickname)
            {
              $repo = Repo::create([
                  'repo_name' => $github_repo['name'],
                  'full_name' => $github_repo['full_name'],
                  #'fork'      => $github_repo['fork'],
                  'private'   => $github_repo['private'],
                  'clone_url' => $github_repo['clone_url'],
                  'user_id'   => $user->id,
              ]);
            }
          }
          else {
            print_r($github_repo);
          }
        }
      }
    }
  }
}
