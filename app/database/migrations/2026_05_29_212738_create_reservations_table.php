<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('guest_name');
            $table->string('guest_phone');
            $table->string('guest_email')->nullable();
            $table->foreignId('table_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('reserved_at');
            $table->unsignedTinyInteger('guest_count')->default(2);
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
