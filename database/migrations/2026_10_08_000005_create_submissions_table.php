<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dealer_id')->constrained()->restrictOnDelete();
            $table->string('drive_link', 2048);
            $table->text('note')->nullable();
            $table->string('status')->default('MENUNGGU');
            $table->timestamp('submitted_at');
            $table->timestamps();

            // satu pengumpulan per dealer per tugas; pengumpulan ulang memperbarui baris ini
            $table->unique(['task_id', 'dealer_id']);
        });

        Schema::create('submission_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('activity');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_logs');
        Schema::dropIfExists('submissions');
    }
};
