<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * DB Connection
     *
     * @return void
     */
    protected $connection = 'mongodb';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      // Schema::connection($this->connection)->table('users', function (Blueprint $collection)
      // {
      //   $collection->unique('id');
      //   $collection->index('name');
      //   $table->index('email')->unique();
      //   $collection->rememberToken();
      //   $collection->timestamps();
      // });
          Schema::create('users', function (Blueprint $table) {
            $table->unique('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
