<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $location = ["main_office","yuzana_tower","downtown"];
        $position = ['Team Lead','Senior Developer','Mid Developer','Junior Developer'];

        return [
            'user_id'=>User::get()->random()->id,
            'name' => fake()->name(),
            'email' => fake()->email(),
            'age' => fake()->numberBetween(18,40),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'department' => "IT Department",
            'location' => fake()->randomElement($location),
            'position' => fake()->randomElement($position),
            'created_at' => Carbon::now()
        ];
    }
}
