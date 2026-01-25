<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('blog_reports')) {
            return;
        }

        Schema::create('blog_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete();
            $table->text('reason');
            $table->string('status')->comment('PENDING, REVIEWED, DISMISSED');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_reports');
    }
};





