<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('edit_user_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('edit_user_profiles', 'edit_type')) {
                $table->string('edit_type')->default('profile');
            }

            if (!Schema::hasColumn('edit_user_profiles', 'image_changes')) {
                $table->json('image_changes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('edit_user_profiles', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('edit_user_profiles', 'edit_type')) {
                $columnsToDrop[] = 'edit_type';
            }

            if (Schema::hasColumn('edit_user_profiles', 'image_changes')) {
                $columnsToDrop[] = 'image_changes';
            }

            if ($columnsToDrop !== []) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
