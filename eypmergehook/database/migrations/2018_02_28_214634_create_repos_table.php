<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReposTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('repos', function (Blueprint $table) {
      $table->increments('id');
      $table->string('repo_name');
      $table->string('full_name')->nullable();
      $table->string('project_name')->nullable();
      $table->string('fork')->nullable();
      $table->boolean('private');
      $table->string('clone_url');
      $table->integer('user_id')->nullable()->references('id')->on('users');
      $table->integer('organization_id')->nullable()->references('id')->on('organizations');
      $table->boolean('webhook')->default(false);
      $table->string('webhook_password')->nullable();
      $table->boolean('telegram_notifications')->default(true);
      $table->string('telegram_chatid')->nullable();
      $table->boolean('autoreleasetags')->default(false);
      $table->boolean('forceautotag')->default(false);
      $table->integer('github_id')->nullable();
      $table->timestamps();
      $table->timestamp('repo_analyzed_on')->nullable();
      $table->boolean('is_puppet_module')->default(false);
      $table->boolean('has_readme')->default(false);
      $table->boolean('has_changelog')->default(false);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('repos');
  }
}
