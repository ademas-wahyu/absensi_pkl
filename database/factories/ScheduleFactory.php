<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'date' => $this->faker->date(),
            'type' => $this->faker->randomElement(['wfh', 'wfo', 'libur']),
            'notes' => $this->faker->optional()->sentence(),
            'created_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the schedule is work from home.
     */
    public function wfh(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'wfh',
        ]);
    }

    /**
     * Indicate that the schedule is work from office.
     */
    public function wfo(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'wfo',
        ]);
    }

    /**
     * Indicate that the schedule is a day off (libur).
     */
    public function libur(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'libur',
        ]);
    }

    /**
     * Indicate that the schedule is for today.
     */
    public function today(): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the schedule is for tomorrow.
     */
    public function tomorrow(): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => now()->addDay()->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the schedule is for a specific date.
     */
    public function forDate(string $date): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => $date,
        ]);
    }

    /**
     * Indicate that the schedule has notes.
     */
    public function withNotes(string $notes): static
    {
        return $this->state(fn (array $attributes) => [
            'notes' => $notes,
        ]);
    }

    /**
     * Indicate that the schedule was created by a specific admin.
     */
    public function createdBy(User $admin): static
    {
        return $this->state(fn (array $attributes) => [
            'created_by' => $admin->id,
        ]);
    }
}
