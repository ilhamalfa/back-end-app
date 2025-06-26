<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penghunis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('foto_ktp'); 
            $table->enum('status_penghuni', ['kontrak', 'tetap']);
            $table->string('no_telp');
            $table->boolean('is_married')->default(false);
            $table->boolean('is_aktif')->default(true);
            $table->foreignId('rumah_id')
                    ->constrained('rumahs')
                    ->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('penghunis');
    }
};
