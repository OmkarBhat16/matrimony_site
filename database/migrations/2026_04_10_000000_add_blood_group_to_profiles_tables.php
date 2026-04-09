<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('user_profile') && ! Schema::hasColumn('user_profile', 'blood_group')) {
            Schema::table('user_profile', function (Blueprint $table): void {
                $table->string('blood_group')->nullable()->after('date_of_birth');
            });
        }

        if (Schema::hasTable('edit_user_profiles') && ! Schema::hasColumn('edit_user_profiles', 'blood_group')) {
            Schema::table('edit_user_profiles', function (Blueprint $table): void {
                $table->string('blood_group')->nullable()->after('date_of_birth');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('user_profile') && Schema::hasColumn('user_profile', 'blood_group')) {
            Schema::table('user_profile', function (Blueprint $table): void {
                $table->dropColumn('blood_group');
            });
        }

        if (Schema::hasTable('edit_user_profiles') && Schema::hasColumn('edit_user_profiles', 'blood_group')) {
            Schema::table('edit_user_profiles', function (Blueprint $table): void {
                $table->dropColumn('blood_group');
            });
        }
    }
};
