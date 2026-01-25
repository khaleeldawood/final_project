<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('events')) {
            return;
        }

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained();
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->string('type');
            $table->string('status')->comment('PENDING, APPROVED, CANCELLED');
            $table->integer('max_organizers')->nullable();
            $table->integer('max_volunteers')->nullable();
            $table->integer('max_attendees')->nullable();
            $table->integer('organizer_points');
            $table->integer('volunteer_points');
            $table->integer('attendee_points');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};





