<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $educationTypes = [
            'SSC',
            'HSC',
            'Graduation',
            'Post-Graduation',
            'Masters',
            'Diploma',
            'Doctorate',
            'Other',
        ];

        Schema::table('user_profile', function (Blueprint $table) use ($educationTypes) {
            $table->enum('education_type', $educationTypes)
                ->nullable()
                ->after('gender');
        });

        Schema::table('edit_user_profiles', function (Blueprint $table) use ($educationTypes) {
            $table->enum('education_type', $educationTypes)
                ->nullable()
                ->after('gender');
        });
    }

    public function down(): void
    {
        Schema::table('edit_user_profiles', function (Blueprint $table) {
            $table->dropColumn('education_type');
        });

        Schema::table('user_profile', function (Blueprint $table) {
            $table->dropColumn('education_type');
        });
    }
};
