<?php

namespace Database\Factories;

use App\Models\AbsentUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AbsentUser>
 */
class AbsentUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AbsentUser::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'absent_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['hadir', 'izin', 'sakit']),
            'reason' => $this->faker->optional()->sentence(),
            'selfie_path' => $this->faker->optional()->filePath(),
            'latitude' => $this->faker->optional()->latitude(-6.5, -6.0),
            'longitude' => $this->faker->optional()->longitude(106.5, 107.0),
            'verification_method' => $this->faker->randomElement(['location', 'selfie', 'manual']),
            'checkout_at' => $this->faker->optional()->dateTime(),
            'early_leave_reason' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the user is present (hadir).
     */
    public function hadir(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'hadir',
            'reason' => null,
        ]);
    }

    /**
     * Indicate that the user has permission (izin).
     */
    public function izin(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'izin',
            'reason' => $this->faker->sentence(),
        ]);
    }

    /**
     * Indicate that the user is sick (sakit).
     */
    public function sakit(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sakit',
            'reason' => $this->faker->sentence(),
        ]);
    }

    /**
     * Indicate that the user has checked out.
     */
    public function checkedOut(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'hadir',
            'checkout_at' => $this->faker->dateTime(),
        ]);
    }

    /**
     * Indicate that the user has checked out early.
     */
    public function earlyCheckout(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'hadir',
            'checkout_at' => $this->faker->dateTime(),
            'early_leave_reason' => $this->faker->sentence(),
        ]);
    }

    /**
     * Indicate that the absence is for today.
     */
    public function today(): static
    {
        return $this->state(fn (array $attributes) => [
            'absent_date' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the absence has location verification.
     */
    public function withLocation(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_method' => 'location',
            'latitude' => $this->faker->latitude(-6.5, -6.0),
            'longitude' => $this->faker->longitude(106.5, 107.0),
        ]);
    }

    /**
     * Indicate that the absence has selfie verification.
     */
    public function withSelfie(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_method' => 'selfie',
            'selfie_path' => 'selfies/' . $this->faker->uuid . '.jpg',
        ]);
    }
}
