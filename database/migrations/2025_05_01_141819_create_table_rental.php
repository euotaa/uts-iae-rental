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
        Schema::create('rental', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id'); // foreign key ke tabel customer
            $table->unsignedBigInteger('device_id'); // foreign key ke tabel device
            $table->string('name'); // nama penyewa atau transaksi
            $table->string('device_name'); // nama iPhone yang disewa
            $table->integer('duration'); // durasi sewa (misalnya hari)
            $table->date('start_date'); // tanggal mulai sewa
            $table->date('end_date'); // tanggal akhir sewa
            $table->decimal('total_price', 10, 2); // total harga sewa
            $table->enum('status', ['booked', 'ongoing', 'returned'])->default('booked'); // status sewa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental');
    }
};
