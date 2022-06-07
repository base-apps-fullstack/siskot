<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileSekolasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qat_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('no_reg', 10)->nullable()->default(null);
            $table->string('nama')->nullable()->default(null);
            $table->text('alamat')->nullable()->default(null);
            $table->string('phone', 20)->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->string('password', 191)->nullable()->default(null);
            $table->enum('status', ['pending', 'approve'])->nullable()->default('pending')->index();
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
        Schema::dropIfExists('qat_sekolah');
    }
}
