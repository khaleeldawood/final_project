<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('universities', function (Blueprint $table) {
            if (!Schema::hasColumn('universities', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('universities', 'logo_url')) {
                $table->string('logo_url')->nullable()->after('description');
            }
            if (!Schema::hasColumn('universities', 'email_domain')) {
                $table->string('email_domain')->after('logo_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('universities', function (Blueprint $table) {
            if (Schema::hasColumn('universities', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('universities', 'logo_url')) {
                $table->dropColumn('logo_url');
            }
            if (Schema::hasColumn('universities', 'email_domain')) {
                $table->dropColumn('email_domain');
            }
        });
    }
};
