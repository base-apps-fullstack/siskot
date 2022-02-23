<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'users';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) {
            return;
        }
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->nullable()->default(null)->index();
            $table->string('fullname')->nullable()->default(null)->index();
            $table->string('email')->nullable()->default(null)->index();
            $table->string('image', 191)->nullable()->default(null);
            $table->enum('status', ['active', 'inactive'])->nullable()->default('active')->index();
            $table->string('password', 191)->nullable()->default(null);
            $table->rememberToken();
            $table->dateTime('last_login')->nullable()->default(null);
            $table->integer('login_attempt')->nullable()->default(null);
            $table->dateTime('login_attempt_at')->nullable()->default(null);
            $table->dateTime('locked_at')->nullable()->default(null);
            $table->unsignedInteger('created_by')->nullable()->default(null);
            $table->unsignedInteger('updated_by')->nullable()->default(null);
            $table->nullableTimestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->set_schema_table);
    }
}
