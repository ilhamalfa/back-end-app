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
        Schema::create('iurans', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['lunas', 'belum bayar']);
            $table->enum('tipe', ['kebersihan', 'satpam']);
            $table->unsignedBigInteger('jumlah');
            $table->string('keterangan');
            $table->date('bulan');
            $table->foreignId('penghuni_id')
                    ->constrained('penghunis')
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
        Schema::dropIfExists('iurans');
    }
};
