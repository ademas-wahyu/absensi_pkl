<?php

namespace Database\Factories;

use App\Models\Sekolah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sekolah>
 */
class SekolahFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sekolah::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_sekolah' => $this->faker->company() . ' School',
            'alamat' => $this->faker->address(),
            'no_telepon' => $this->faker->optional()->phoneNumber(),
        ];
    }

    /**
     * Indicate that the sekolah has no phone number.
     */
    public function withoutPhone(): static
    {
        return $this->state(fn (array $attributes) => [
            'no_telepon' => null,
        ]);
    }

    /**
     * Indicate that the sekolah has a specific name.
     */
    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_sekolah' => $name,
        ]);
    }

    /**
     * Indicate that the sekolah is located in Jakarta.
     */
    public function jakarta(): static
    {
        return $this->state(fn (array $attributes) => [
            'alamat' => $this->faker->streetAddress() . ', Jakarta ' . $this->faker->randomElement(['Selatan', 'Pusat', 'Barat', 'Timur', 'Utara']),
        ]);
    }

    /**
     * Indicate that the sekolah is a vocational school (SMK).
     */
    public function smk(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_sekolah' => 'SMK ' . $this->faker->company(),
        ]);
    }

    /**
     * Indicate that the sekolah is a high school (SMA).
     */
    public function sma(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_sekolah' => 'SMA ' . $this->faker->company(),
        ]);
    }

    /**
     * Indicate that the sekolah is a university.
     */
    public function university(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_sekolah' => 'Universitas ' . $this->faker->company(),
        ]);
    }
}
