<?php

namespace Database\Factories;

use App\Models\DivisiAdmin;
use App\Models\Mentor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mentor>
 */
class MentorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Mentor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "nama_mentor" => $this->faker->name(),
            "email" => $this->faker->unique()->safeEmail(),
            "no_telepon" => $this->faker->optional()->phoneNumber(),
            "divisi_id" => DivisiAdmin::factory(),
            "keahlian" => $this->faker->randomElement([
                "SEO",
                "Web Development",
                "Mobile Development",
                "Data Analysis",
                "UI/UX Design",
                "Digital Marketing",
            ]),
        ];
    }

    /**
     * Indicate that the mentor has no divisi.
     */
    public function withoutDivisi(): static
    {
        return $this->state(
            fn(array $attributes) => [
                "divisi_id" => null,
            ],
        );
    }

    /**
     * Indicate that the mentor belongs to a specific divisi.
     */
    public function forDivisi(int $divisiId): static
    {
        return $this->state(
            fn(array $attributes) => [
                "divisi_id" => $divisiId,
            ],
        );
    }

    /**
     * Indicate that the mentor has specific expertise.
     */
    public function expertise(string $keahlian): static
    {
        return $this->state(
            fn(array $attributes) => [
                "keahlian" => $keahlian,
            ],
        );
    }

    /**
     * Indicate that the mentor is SEO specialist.
     */
    public function seo(): static
    {
        return $this->state(
            fn(array $attributes) => [
                "keahlian" => "SEO",
            ],
        );
    }

    /**
     * Indicate that the mentor is Web Developer.
     */
    public function webDeveloper(): static
    {
        return $this->state(
            fn(array $attributes) => [
                "keahlian" => "Web Development",
            ],
        );
    }

    /**
     * Indicate that the mentor is Mobile Developer.
     */
    public function mobileDeveloper(): static
    {
        return $this->state(
            fn(array $attributes) => [
                "keahlian" => "Mobile Development",
            ],
        );
    }

    /**
     * Indicate that the mentor is Data Analyst.
     */
    public function dataAnalyst(): static
    {
        return $this->state(
            fn(array $attributes) => [
                "keahlian" => "Data Analysis",
            ],
        );
    }

    /**
     * Indicate that the mentor is UI/UX Designer.
     */
    public function uiUxDesigner(): static
    {
        return $this->state(
            fn(array $attributes) => [
                "keahlian" => "UI/UX Design",
            ],
        );
    }

    /**
     * Indicate that the mentor is Digital Marketer.
     */
    public function digitalMarketer(): static
    {
        return $this->state(
            fn(array $attributes) => [
                "keahlian" => "Digital Marketing",
            ],
        );
    }

    /**
     * Indicate that the mentor has no phone number.
     */
    public function withoutPhone(): static
    {
        return $this->state(
            fn(array $attributes) => [
                "no_telepon" => null,
            ],
        );
    }
}
