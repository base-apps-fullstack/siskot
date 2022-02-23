<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'menus';

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
            $table->integer('parent_id')->nullable()->default(null);
            $table->string('name', 191)->nullable()->default(null);
            $table->string('classification', 191)->nullable()->default(null);
            $table->string('icon', 191)->nullable()->default(null);
            $table->string('url', 191)->nullable()->default(null);
            $table->text('actions')->nullable()->default(null);
            $table->integer('order_classification')->nullable()->default(null);
            $table->integer('order_inner_classification')->nullable()->default(null);
            $table->boolean('is_collapse')->nullable()->default(null)->default(0);
            $table->nullableTimestamps();
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
