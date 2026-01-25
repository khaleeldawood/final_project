<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('blogs')) {
            return;
        }

        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->nullable()->constrained();
            $table->foreignId('author_id')->constrained('users');
            $table->string('title');
            $table->text('content');
            $table->string('category');
            $table->string('status')->comment('PENDING, APPROVED, REJECTED');
            $table->boolean('is_global')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};





