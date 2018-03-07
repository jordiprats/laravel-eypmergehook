<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersExtraOptions extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->boolean('telegram_notifications')->default(true);
      $table->string('telegram_chatid')->nullable();
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
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('telegram_notifications');
      $table->dropColumn('telegram_chatid');
    });
  }
}
