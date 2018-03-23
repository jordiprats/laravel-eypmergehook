<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('organizations', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nickname');
      $table->string('description')->nullable();
      $table->string('avatar_url')->nullable();
      $table->string('url')->nullable();
      $table->integer('github_id')->nullable();
      $table->boolean('telegram_notifications')->default(true);
      $table->string('telegram_chatid')->nullable();
      $table->timestamps();
      $table->timestamp('github_repos_updated_on')->nullable();
      $table->timestamp('github_organizations_updated_on')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('organizations');
  }
}
