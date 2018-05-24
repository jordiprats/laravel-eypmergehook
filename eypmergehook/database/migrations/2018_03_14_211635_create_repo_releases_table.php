<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepoReleasesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('repo_releases', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('repo_id')->references('id')->on('repos');
      $table->string('release_name');
      $table->boolean('is_latest')->default(false);
      $table->boolean('private')->default(false);
      $table->boolean('has_readme')->default(false);
      $table->boolean('has_changelog')->default(false);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('repo_releases');
  }
}
