<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("user_profile", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("user_id")
                ->constrained("users")
                ->onDelete("cascade");
            $table->string("full_name")->nullable();
            $table->string("navras_naav")->nullable();
            $table->string("gender")->nullable();
            $table->string("education")->nullable();
            $table->string("occupation")->nullable();
            $table->decimal("annual_income", 15, 2)->nullable();
            $table->date("date_of_birth")->nullable();
            $table->string("day_and_time_of_birth")->nullable();
            $table->string("place_of_birth")->nullable();
            $table->string("jaath")->nullable();
            $table->string("height_cm__Oonchi")->nullable();
            $table->string("skin_complexion__Rang")->nullable();
            $table->string("zodiac_sign__Raas")->nullable();
            $table->string("naadi")->nullable();
            $table->string("gann")->nullable();
            $table->string("devak")->nullable();
            $table->string("kul_devata")->nullable();
            $table->string("fathers_name")->nullable();
            $table->string("mothers_name")->nullable();
            $table->string("marital_status")->nullable();
            $table->string("siblings")->nullable();
            $table->string("uncles")->nullable();
            $table->string("aunts")->nullable();
            $table->text("mumbai_address")->nullable();
            $table->text("village_address")->nullable();
            $table->string("village_farm")->nullable();
            $table->text("naathe_relationships")->nullable();
            $table->decimal("weight_kg", 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("user_profile");
    }
};
