<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qat_siswa', function (Blueprint $table) {
            $table->id();
            $table->string('no_sekolah', 10)->nullable()->default(null);
            $table->string('no_register', 10)->nullable()->default(null);
            $table->string('nis')->nullable()->default(null);
            $table->string('nisn')->nullable()->default(null);
            $table->string('nama_lengkap')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->string('jk', 1)->nullable()->default(null);
            $table->date('tgl_lahir')->nullable()->default(null);
            $table->string('provinsi')->nullable()->default(null);
            $table->string('kota')->nullable()->default(null);
            $table->text('alamat')->nullable()->default(null);
            $table->string('nama_ayah')->nullable()->default(null);
            $table->string('nama_ibu')->nullable()->default(null);
            $table->string('nama_wali')->nullable()->default(null);
            $table->string('pekerjaan_ayah')->nullable()->default(null);
            $table->string('pekerjaan_ibu')->nullable()->default(null);
            $table->string('pekerjaan_wali')->nullable()->default(null);
            $table->string('tlp_ortu_wali')->nullable()->default(null);
            $table->string('tahun_masuk')->nullable()->default(null);
            $table->string('asal_sekolah')->nullable()->default(null);
            $table->string('no_kelas')->nullable()->default(null);
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
        Schema::dropIfExists('qat_siswa');
    }
}
