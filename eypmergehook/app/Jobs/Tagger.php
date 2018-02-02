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

  protected $username;
  protected $repo;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(string $username, string $repo)
  {
    $this->repo = $repo;
    $this->username = $username;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $cmd="docker run -d -e GITHUB_USERNAME=".$this->username." -v /root/.ssh:/root/.ssh -t eyp/eyptagger /bin/bash /usr/bin/updatetags.sh ".$this->repo;
    echo "tagging /".$cmd."/: ".exec($cmd)."\n";
  }
}
