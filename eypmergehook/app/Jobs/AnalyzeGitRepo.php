<?php

namespace App\Jobs;

use App\Repo;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AnalyzeGitRepo implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $username;
  protected $reponame;
  protected $repo;

  /**
   * Create a new job instance.
   *
   * @return void
   */
   public function __construct($username, $repo)
   {
     $this->reponame = $repo;
     $this->username = $username;
   }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    if(Repo::where(['full_name' => $this->username."/".$this->reponame])->count() == 1)
    {
      $this->repo=Repo::where(['full_name' => $this->username."/".$this->reponame])->first();

      $date24hoursAgo = strtotime("-24 hours");
      if((!$repo->repo_analyzed_on) || ($repo->repo_analyzed_on < $date24hoursAgo))
      {
        Log::info("AnalyzeGitRepo: ".$this->repo->clone_url);

        $repo_info_output=shell_exec("docker run -i -v /root/.ssh:/root/.ssh -t eyp/gitrepoinfo /bin/bash /usr/bin/report.sh ".$this->repo->clone_url);
        $repo_info_json = json_decode($repo_info_output, true);


        $this->repo->is_puppet_module = $repo_info_json[$this->reponame]['is_puppet_module'];
        $this->repo->has_readme = $repo_info_json[$this->reponame]['has_readme'];
        $this->repo->has_changelog = $repo_info_json[$this->reponame]['has_changelog'];

        $this->repo->repo_analyzed_on = Carbon::now();

        $this->repo->save();        
      }
    }
    else
    {
      Log::info("WTF - AnalyzeGitRepo: ".$this->username."/".$this->reponame." count: ".Repo::where(['full_name' => $this->username."/".$this->reponame])->count());
    }
  }
}
