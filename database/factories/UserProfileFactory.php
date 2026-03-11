<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => User::factory(),
            "full_name" => $this->faker->name(),
            "navras_naav" => $this->faker->firstName(),
            "gender" => $this->faker->randomElement([
                "male",
                "female",
                "other",
            ]),
            "education" => $this->faker->word(),
            "occupation" => $this->faker->jobTitle(),
            "annual_income" => $this->faker->randomFloat(2, 100000, 5000000),
            "date_of_birth" => $this->faker
                ->dateTimeBetween("-40 years", "-18 years")
                ->format("Y-m-d"),
            "day_and_time_of_birth" =>
                $this->faker->dayOfWeek() . " " . $this->faker->time(),
            "place_of_birth" => $this->faker->city(),
            "jaath" => $this->faker->word(),
            "height_cm__Oonchi" => (string) $this->faker->numberBetween(
                140,
                195,
            ),
            "skin_complexion__Rang" => $this->faker->word(),
            "zodiac_sign__Raas" => $this->faker->word(),
            "naadi" => $this->faker->word(),
            "gann" => $this->faker->word(),
            "devak" => $this->faker->word(),
            "kul_devata" => $this->faker->word(),
            "fathers_name" => $this->faker->name("male"),
            "mothers_name" => $this->faker->name("female"),
            "marital_status" => $this->faker->randomElement([
                "single",
                "married",
                "divorced",
                "widowed",
            ]),
            "siblings" => $this->faker->sentence(),
            "uncles" => $this->faker->sentence(),
            "aunts" => $this->faker->sentence(),
            "mumbai_address" => $this->faker->address(),
            "village_address" => $this->faker->address(),
            "village_farm" => $this->faker->word(),
            "naathe_relationships" => $this->faker->text(),
        ];
    }
}
