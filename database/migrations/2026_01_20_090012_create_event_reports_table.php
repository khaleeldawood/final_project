<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('event_reports')) {
            return;
        }

        Schema::create('event_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete();
            $table->text('reason');
            $table->string('status')->comment('PENDING, REVIEWED, DISMISSED');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_reports');
    }
};





