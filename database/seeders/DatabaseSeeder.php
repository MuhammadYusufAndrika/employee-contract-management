<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Contract;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default super admin user
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'), // Password: password
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Password: password
            'role' => User::ROLE_ADMIN,
        ]);

        // Create a viewer user for testing
        User::factory()->create([
            'name' => 'Viewer User',
            'email' => 'viewer@example.com',
            'password' => bcrypt('password'), // Password: password
            'role' => User::ROLE_VIEWER,
        ]);

        // Create employees with contracts
        $departments = ['Engineering', 'HR', 'Finance', 'Operations', 'Marketing', 'IT', 'Legal'];
        $workLocations = ['Jakarta HQ', 'Bandung Office', 'Surabaya Branch', 'Remote'];
        $contractTypes = ['Kontrak', 'KPP']; // Changed from PKWT to Kontrak
        $jobPositions = ['Staff', 'Senior Staff', 'Supervisor', 'Manager', 'Senior Manager'];

        // Create 30 employees
        for ($i = 1; $i <= 30; $i++) {
            $employee = Employee::create([
                'employee_name' => fake()->name(),
                'nik' => 'NIK' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'birthplace' => fake()->city(),
                'birthdate' => fake()->dateTimeBetween('-45 years', '-22 years'),
                'address' => fake()->address(),
                'nomor_hp' => '08' . fake()->numerify('##########'),
            ]);

            // Create 1-3 contracts per employee (showing contract history)
            $contractCount = rand(1, 3);

            for ($j = 0; $j < $contractCount; $j++) {
                $contractType = $j === ($contractCount - 1) && rand(0, 1) ? 'KPP' : 'Kontrak'; // Last contract might be permanent
                $startDate = now()->subMonths(rand(6, 36))->subMonths($j * 12);

                Contract::create([
                    'employee_id' => $employee->id,
                    'nomor_kontrak' => 'CTR/' . date('Y', strtotime($startDate)) . '/' . str_pad($employee->id * 10 + $j, 4, '0', STR_PAD_LEFT),
                    'job_position' => fake()->randomElement($jobPositions),
                    'point_of_hire' => fake()->randomElement(['Direct Hire', 'Internal Promotion', 'Transfer']),
                    'TMT_awal' => $startDate,
                    'contract_type' => $contractType,
                    'start_date' => $startDate,
                    'end_date' => $contractType === 'KPP' ? null : (clone $startDate)->addYear(),
                    'department' => fake()->randomElement($departments),
                    'work_location' => fake()->randomElement($workLocations),
                ]);
            }
        }

        // Create some contracts expiring soon (within 30 days)
        $employees = Employee::inRandomOrder()->limit(8)->get();
        foreach ($employees as $employee) {
            Contract::create([
                'employee_id' => $employee->id,
                'nomor_kontrak' => 'CTR/EXPIRING/' . str_pad($employee->id, 4, '0', STR_PAD_LEFT),
                'job_position' => fake()->randomElement($jobPositions),
                'point_of_hire' => 'Contract Renewal',
                'TMT_awal' => now()->subMonths(11),
                'contract_type' => 'Kontrak',
                'start_date' => now()->subMonths(11),
                'end_date' => now()->addDays(rand(1, 30)),
                'department' => fake()->randomElement($departments),
                'work_location' => fake()->randomElement($workLocations),
            ]);
        }

        // Create some expired contracts
        $employees = Employee::inRandomOrder()->limit(5)->get();
        foreach ($employees as $employee) {
            Contract::create([
                'employee_id' => $employee->id,
                'nomor_kontrak' => 'CTR/EXPIRED/' . str_pad($employee->id, 4, '0', STR_PAD_LEFT),
                'job_position' => fake()->randomElement($jobPositions),
                'point_of_hire' => 'Expired Contract',
                'TMT_awal' => now()->subMonths(13),
                'contract_type' => 'Kontrak',
                'start_date' => now()->subMonths(13),
                'end_date' => now()->subDays(rand(1, 30)),
                'department' => fake()->randomElement($departments),
                'work_location' => fake()->randomElement($workLocations),
            ]);
        }
    }
}
