<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('user_profile') && ! Schema::hasColumn('user_profile', 'native_address')) {
            Schema::table('user_profile', function (Blueprint $table): void {
                $table->text('native_address')->nullable()->after('address');
            });
        }

        if (Schema::hasTable('edit_user_profiles') && ! Schema::hasColumn('edit_user_profiles', 'native_address')) {
            Schema::table('edit_user_profiles', function (Blueprint $table): void {
                $table->text('native_address')->nullable()->after('address');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('user_profile') && Schema::hasColumn('user_profile', 'native_address')) {
            Schema::table('user_profile', function (Blueprint $table): void {
                $table->dropColumn('native_address');
            });
        }

        if (Schema::hasTable('edit_user_profiles') && Schema::hasColumn('edit_user_profiles', 'native_address')) {
            Schema::table('edit_user_profiles', function (Blueprint $table): void {
                $table->dropColumn('native_address');
            });
        }
    }
};
