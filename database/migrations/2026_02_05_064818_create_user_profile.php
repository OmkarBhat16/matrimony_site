<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_profile', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->string('address');  
            $table->string('phone_number')->unique();
            $table->text('bio');   
            $table->string('profile_picture');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed']);
            $table->string('education');
            $table->string('occupation');
            $table->decimal('annual_income', 15, 2);
            $table->string('religion');
            $table->string('caste');
            $table->string('mother_tongue');
            $table->string('state');
            $table->string('city');
            $table->integer('height_cm');
            $table->decimal('weight_kg', 5, 2);
            $table->enum('dietary_preferences', ['vegetarian', 'non-vegetarian', 'vegan']);
            $table->enum('smoking_habits', ['non-smoker', 'occasional', 'regular']);
            $table->enum('drinking_habits', ['non-drinker', 'occasional', 'regular']);  
            $table->text('hobbies_interests');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profile');
    }
};
