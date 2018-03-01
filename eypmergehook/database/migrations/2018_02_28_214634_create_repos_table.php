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
      $table->integer('user_id')->nullable()->references('id')->on('users');
      $table->integer('organization_id')->nullable()->references('id')->on('organizations');
      $table->boolean('fork');
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
    Schema::dropIfExists('repos');
  }
}
