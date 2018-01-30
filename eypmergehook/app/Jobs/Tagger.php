<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Tagger implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected string $repo;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(string $repo)
  {
    $this->repo = $repo;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    if($repo!=NULL) throw new Exception ('repo is NULL');
    if(strlen($repo)<=0) throw new Exception ('repo is an empty string');

    $cmd="docker run -d -v /root/.ssh:/root/.ssh -t eyp/eyptagger /bin/bash /usr/bin/updatetags.sh ".$this->repo;
    echo "tagging /".$cmd."/: ".exec($cmd)."\n";
  }
}
