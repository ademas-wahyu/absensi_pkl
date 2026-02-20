<?php

namespace Database\Factories;

use App\Models\DivisiAdmin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DivisiAdmin>
 */
class DivisiAdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DivisiAdmin::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_divisi' => $this->faker->randomElement([
                'SEO',
                'Project',
                'Design',
                'Development',
                'Marketing',
                'Content',
                'Analytics',
                'Operations',
            ]),
            'deskripsi' => $this->faker->optional()->paragraph(),
        ];
    }

    /**
     * Indicate that the divisi has no description.
     */
    public function withoutDescription(): static
    {
        return $this->state(fn (array $attributes) => [
            'deskripsi' => null,
        ]);
    }

    /**
     * Indicate that the divisi is SEO.
     */
    public function seo(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_divisi' => 'SEO',
            'deskripsi' => 'Search Engine Optimization Division - Mengelola optimasi mesin pencari dan ranking website',
        ]);
    }

    /**
     * Indicate that the divisi is Project.
     */
    public function project(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_divisi' => 'Project',
            'deskripsi' => 'Project Management Division - Mengelola dan mengkoordinasi proyek-proyek',
        ]);
    }

    /**
     * Indicate that the divisi is Design.
     */
    public function design(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_divisi' => 'Design',
            'deskripsi' => 'Design Division - Membuat desain visual dan UI/UX',
        ]);
    }

    /**
     * Indicate that the divisi is Development.
     */
    public function development(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_divisi' => 'Development',
            'deskripsi' => 'Development Division - Pengembangan aplikasi dan website',
        ]);
    }

    /**
     * Indicate that the divisi is Marketing.
     */
    public function marketing(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_divisi' => 'Marketing',
            'deskripsi' => 'Marketing Division - Strategi pemasaran dan promosi',
        ]);
    }

    /**
     * Indicate that the divisi is Content.
     */
    public function content(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_divisi' => 'Content',
            'deskripsi' => 'Content Division - Pembuatan dan pengelolaan konten',
        ]);
    }

    /**
     * Indicate that the divisi is Analytics.
     */
    public function analytics(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_divisi' => 'Analytics',
            'deskripsi' => 'Analytics Division - Analisis data dan insight',
        ]);
    }

    /**
     * Indicate that the divisi is Operations.
     */
    public function operations(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_divisi' => 'Operations',
            'deskripsi' => 'Operations Division - Operasional dan administrasi',
        ]);
    }

    /**
     * Indicate that the divisi has a specific name.
     */
    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_divisi' => $name,
        ]);
    }

    /**
     * Indicate that the divisi has a specific description.
     */
    public function withDescription(string $description): static
    {
        return $this->state(fn (array $attributes) => [
            'deskripsi' => $description,
        ]);
    }
}
