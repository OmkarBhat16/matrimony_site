<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('edit_user_profiles', function (Blueprint $table) {
            $table->string('edit_type')->default('profile')->after('user_id');
            $table->json('image_changes')->nullable()->after('naathe_relationships');
        });
    }

    public function down(): void
    {
        Schema::table('edit_user_profiles', function (Blueprint $table) {
            $table->dropColumn('edit_type');
            $table->dropColumn('image_changes');
        });
    }
};
