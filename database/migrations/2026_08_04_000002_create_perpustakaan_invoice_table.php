<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('perpustakaan_invoice', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_invoice')->unique();
            $table->date('tanggal_invoice');
            $table->string('nama_suplier');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('perpustakaan_buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('perpustakaan_invoice')->onDelete('cascade');
            $table->string('judul_buku');
            $table->string('isbn')->nullable();
            $table->string('penerbit');
            $table->string('tahun_terbit');
            $table->integer('jumlah_eksemplar')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perpustakaan_buku');
        Schema::dropIfExists('perpustakaan_invoice');
    }
};
