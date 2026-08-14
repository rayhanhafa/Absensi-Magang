<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intern_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('waktu_masuk')->nullable();
            $table->time('waktu_pulang')->nullable();
            $table->enum('status', ['hadir', 'terlambat', 'izin', 'sakit', 'alpa']);
            $table->unsignedSmallInteger('keterlambatan')->default(0);
            $table->text('catatan')->nullable();

            // Disiapkan untuk fitur lanjutan (geolocation & selfie),
            // tidak wajib diisi pada MVP.
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('foto_check_in')->nullable();
            $table->string('foto_check_out')->nullable();

            $table->timestamps();

            $table->unique(['intern_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};