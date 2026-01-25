<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tableName = 'users';
        $hasEmailVerifiedAt = Schema::hasColumn($tableName, 'email_verified_at');
        $hasRememberToken = Schema::hasColumn($tableName, 'remember_token');
        $hasUniversityId = Schema::hasColumn($tableName, 'university_id');
        $hasEmailVerified = Schema::hasColumn($tableName, 'email_verified');
        $hasRole = Schema::hasColumn($tableName, 'role');
        $hasPoints = Schema::hasColumn($tableName, 'points');
        $hasCurrentBadgeId = Schema::hasColumn($tableName, 'current_badge_id');

        Schema::table($tableName, function (Blueprint $table) use ($hasEmailVerifiedAt, $hasRememberToken, $hasUniversityId, $hasEmailVerified, $hasRole, $hasPoints, $hasCurrentBadgeId) {
            if ($hasEmailVerifiedAt) {
                $table->dropColumn('email_verified_at');
            }
            if ($hasRememberToken) {
                $table->dropColumn('remember_token');
            }

            if (!$hasUniversityId) {
                $table->foreignId('university_id')->nullable()->constrained();
            }
            if (!$hasEmailVerified) {
                $table->boolean('email_verified')->default(false);
            }
            if (!$hasRole) {
                $table->string('role')->comment('STUDENT, SUPERVISOR, ADMIN');
            }
            if (!$hasPoints) {
                $table->integer('points')->default(0);
            }
            if (!$hasCurrentBadgeId) {
                $table->foreignId('current_badge_id')->nullable()->constrained('badges')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'university_id')) {
                $table->dropConstrainedForeignId('university_id');
            }
            if (Schema::hasColumn('users', 'current_badge_id')) {
                $table->dropConstrainedForeignId('current_badge_id');
            }
            $columnsToDrop = [];
            foreach (['email_verified', 'role', 'points'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $columnsToDrop[] = $column;
                }
            }
            if ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            }

            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken();
            }
        });
    }
};
