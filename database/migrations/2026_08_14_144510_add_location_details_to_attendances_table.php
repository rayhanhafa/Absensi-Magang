<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedSmallInteger('accuracy_check_in')->nullable()->after('foto_check_in');
            $table->unsignedSmallInteger('accuracy_check_out')->nullable()->after('foto_check_out');
            $table->decimal('distance_check_in', 8, 2)->nullable()->after('accuracy_check_in');
            $table->decimal('distance_check_out', 8, 2)->nullable()->after('accuracy_check_out');
            $table->enum('location_status_check_in', ['valid', 'invalid'])->nullable()->after('distance_check_in');
            $table->enum('location_status_check_out', ['valid', 'invalid'])->nullable()->after('distance_check_out');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'accuracy_check_in',
                'accuracy_check_out',
                'distance_check_in',
                'distance_check_out',
                'location_status_check_in',
                'location_status_check_out',
            ]);
        });
    }
};