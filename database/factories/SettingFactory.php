<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->word(),
            'value' => $this->faker->word(),
        ];
    }

    /**
     * Indicate that the setting is for office latitude.
     */
    public function officeLatitude(): static
    {
        return $this->state(fn (array $attributes) => [
            'key' => 'office_latitude',
            'value' => '-6.175110',
        ]);
    }

    /**
     * Indicate that the setting is for office longitude.
     */
    public function officeLongitude(): static
    {
        return $this->state(fn (array $attributes) => [
            'key' => 'office_longitude',
            'value' => '106.865039',
        ]);
    }

    /**
     * Indicate that the setting is for office radius.
     */
    public function officeRadius(): static
    {
        return $this->state(fn (array $attributes) => [
            'key' => 'office_radius_meters',
            'value' => '100',
        ]);
    }

    /**
     * Indicate that location validation is enabled.
     */
    public function locationValidationEnabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'key' => 'location_validation_enabled',
            'value' => 'true',
        ]);
    }

    /**
     * Indicate that location validation is disabled.
     */
    public function locationValidationDisabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'key' => 'location_validation_enabled',
            'value' => 'false',
        ]);
    }

    /**
     * Indicate that the setting has a specific key.
     */
    public function withKey(string $key): static
    {
        return $this->state(fn (array $attributes) => [
            'key' => $key,
        ]);
    }

    /**
     * Indicate that the setting has a specific value.
     */
    public function withValue(string $value): static
    {
        return $this->state(fn (array $attributes) => [
            'value' => $value,
        ]);
    }

    /**
     * Create all office location settings at once (helper method).
     * This is not a state, but a method to create multiple settings.
     *
     * Usage: SettingFactory::new()->createOfficeLocationSettings();
     */
    public function createOfficeLocationSettings(): array
    {
        return [
            static::new()->officeLatitude()->create(),
            static::new()->officeLongitude()->create(),
            static::new()->officeRadius()->create(),
            static::new()->locationValidationEnabled()->create(),
        ];
    }

    /**
     * Indicate that the setting is for app name.
     */
    public function appName(): static
    {
        return $this->state(fn (array $attributes) => [
            'key' => 'app_name',
            'value' => 'Absensi PKL',
        ]);
    }

    /**
     * Indicate that the setting is for app timezone.
     */
    public function timezone(): static
    {
        return $this->state(fn (array $attributes) => [
            'key' => 'timezone',
            'value' => 'Asia/Jakarta',
        ]);
    }

    /**
     * Indicate that the setting is for check-in time limit.
     */
    public function checkInTimeLimit(): static
    {
        return $this->state(fn (array $attributes) => [
            'key' => 'check_in_time_limit',
            'value' => '09:00',
        ]);
    }

    /**
     * Indicate that the setting is for check-out time limit.
     */
    public function checkOutTimeLimit(): static
    {
        return $this->state(fn (array $attributes) => [
            'key' => 'check_out_time_limit',
            'value' => '17:00',
        ]);
    }

    /**
     * Indicate that the setting is for late tolerance in minutes.
     */
    public function lateTolerance(): static
    {
        return $this->state(fn (array $attributes) => [
            'key' => 'late_tolerance_minutes',
            'value' => '15',
        ]);
    }

    /**
     * Indicate that the setting is boolean true.
     */
    public function booleanTrue(): static
    {
        return $this->state(fn (array $attributes) => [
            'value' => 'true',
        ]);
    }

    /**
     * Indicate that the setting is boolean false.
     */
    public function booleanFalse(): static
    {
        return $this->state(fn (array $attributes) => [
            'value' => 'false',
        ]);
    }

    /**
     * Indicate that the setting is numeric.
     */
    public function numeric(int $number): static
    {
        return $this->state(fn (array $attributes) => [
            'value' => (string) $number,
        ]);
    }

    /**
     * Indicate that the setting is JSON.
     */
    public function json(array $data): static
    {
        return $this->state(fn (array $attributes) => [
            'value' => json_encode($data),
        ]);
    }
}
