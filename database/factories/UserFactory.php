<?php

namespace Database\Factories;

use App\Models\Campus;
use App\Models\Course;
use App\Models\Organization;
use App\Models\Program;
use App\Models\Sport;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstname' => fake()->firstName(),
            'middlename' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'birthdate' => fake()->dateTimeBetween('-2 years', 'now'),
            'gender' => 'Male',
            'address' => fake()->address(),
            'contact' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'year_level' => fake()->numberBetween(1, 4),
            'sport_id' => Sport::pluck('id')->random(),
            // 'course_id' => Course::pluck('id')->random(),
            // 'campus_id' => Campus::pluck('id')->random(),
            // 'program_id' => Program::pluck('id')->random(),
            'organization_id' => Organization::pluck('id')->random(),
            'avatar' => 'asd',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
