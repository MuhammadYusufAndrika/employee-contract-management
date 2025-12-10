<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-2 years', 'now');
        $contractType = $this->faker->randomElement(['Kontrak', 'KPP']);
        $endDate = $contractType === 'KPP' ? null : $this->faker->dateTimeBetween($startDate, '+2 years');
        $birthDate = $this->faker->dateTimeBetween('-60 years', '-20 years');
        $year = Carbon::parse($startDate)->year;
        $contractNumber = 'CTR-' . $year . '-' . $this->faker->unique()->numberBetween(1000, 9999);

        return [
            'employee_name' => $this->faker->name(),
            'nik' => $this->faker->unique()->numerify('################'), // 16 digit NIK
            'nomor_kontrak' => $contractNumber,
            'birthdate' => $birthDate,
            'birthplace' => $this->faker->city(),
            'address' => $this->faker->address(),
            'job_position' => $this->faker->randomElement([
                'Software Engineer',
                'Senior Developer',
                'Project Manager',
                'Business Analyst',
                'HR Manager',
                'Finance Officer',
                'Marketing Executive',
                'Sales Representative',
                'Operations Manager',
                'Customer Service',
                'IT Support',
                'Administrative Staff'
            ]),
            'point_of_hire' => $this->faker->randomElement([
                'Head Office',
                'Branch A',
                'Branch B',
                'Regional Office Jakarta',
                'Regional Office Surabaya',
                'Regional Office Bandung'
            ]),
            'contract_type' => $contractType,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'department' => $this->faker->randomElement([
                'IT',
                'Human Resources',
                'Finance',
                'Marketing',
                'Sales',
                'Operations',
                'Customer Service',
                'Research & Development'
            ]),
            'work_location' => $this->faker->randomElement([
                'Head Office',
                'Branch A',
                'Branch B',
                'Regional Office',
                'Remote',
                'Downtown Office'
            ]),
        ];
    }

    /**
     * Indicate that the contract is expiring soon.
     */
    public function expiringSoon(): static
    {
        return $this->state(fn(array $attributes) => [
            'start_date' => Carbon::now()->subYear(),
            'end_date' => Carbon::now()->addDays(rand(1, 30)),
        ]);
    }

    /**
     * Indicate that the contract has expired.
     */
    public function expired(): static
    {
        return $this->state(fn(array $attributes) => [
            'start_date' => Carbon::now()->subYears(2),
            'end_date' => Carbon::now()->subDays(rand(1, 90)),
        ]);
    }
}
