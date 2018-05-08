<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Jenssegers\Optimus\Optimus;



class Tagger implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $username;
  protected $repo;
  protected $optimus;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(string $username, string $repo)
  {
    $this->repo = $repo;
    $this->username = $username;
    $this->optimus = new Optimus(1580030173, 59260789, 1163945558);
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    // $encoded = $optimus->encode(20);
    if(strlen(config('telegrameyptagger.TELEGRAMTOKEN'))<=0)
    {
      $env="-e GITHUB_USERNAME=".$this->username;
    }
    else
    {
      $env="-e GITHUB_USERNAME=".$this->username." -e TELEGRAMTOKEN=".config('telegrameyptagger.TELEGRAMTOKEN')." -e TELEGRAMCHATID=".config('telegrameyptagger.TELEGRAMCHATID');
    }
    $cmd="docker run -i -e ENABLE_DEBUG=1 ".$env." -v /root/.ssh:/root/.ssh -t eyp/eyptagger /bin/bash /usr/bin/updatetags.sh ".$this->repo;
    echo "tagging /".$cmd."/: ".exec($cmd)."\n";
  }
}
