<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AnalyzeGitRepo implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $repo_url;

  /**
   * Create a new job instance.
   *
   * @return void
   */
   public function __construct($repo_url)
   {
     $this->repo_url = $repo_url;
   }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    Log::info("AnalyzeGitRepo: ".$this->repo_url);

    $repo_info_output="docker run -i -v /root/.ssh:/root/.ssh -t eyp/gitrepoinfo /bin/bash /usr/bin/report.sh ".$this->repo_url;

    $repo_info_json = json_decode($repo_info_output, true);

    //https://stackoverflow.com/questions/4343596/how-can-i-parse-a-json-file-with-php?utm_medium=organic&utm_source=google_rich_qa&utm_campaign=google_rich_qa
    // echo $json_a['John'][status];
    // echo $json_a['Jennifer'][status];
  }
}
