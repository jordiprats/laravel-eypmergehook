<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GrahamCampbell\GitHub\Facades\GitHub;
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
    $user = User::where(['nickname' => $username])->first();

    if($user)
    {
      $github_account=LinkedSocialAccount::where(['user_id' => $user->id, 'provider' => 'github'])->first();
      if($github_account)
      {
        $repos = GitHub::users()->repositories($user->nickname);

        print_r($repos);
        # $repos = $client->api('user')->repositories('KnpLabs');
        # $issue = $client->api('issue')->show('KnpLabs', 'php-github-api', 1);
        // GitHub::connection('main')->issues()->show('GrahamCampbell', 'Laravel-GitHub', 2);
        // GitHub::issues()->show('GrahamCampbell', 'Laravel-GitHub', 2);
        // GitHub::connection()->issues()->show('GrahamCampbell', 'Laravel-GitHub', 2);
      }
    }
  }
}
