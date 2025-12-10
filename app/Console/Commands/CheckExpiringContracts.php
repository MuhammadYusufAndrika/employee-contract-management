<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contract;
use Illuminate\Support\Facades\Log;

class CheckExpiringContracts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contracts:check-expiring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for contracts expiring within 30 days and log them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expiring contracts...');

        $expiringContracts = Contract::expiringWithinDays(30);

        if ($expiringContracts->count() > 0) {
            $this->warn("Found {$expiringContracts->count()} contract(s) expiring within 30 days:");

            foreach ($expiringContracts as $contract) {
                $daysLeft = $contract->daysUntilExpiration();
                $message = sprintf(
                    "- %s (Department: %s) - Contract expires in %d days (End Date: %s)",
                    $contract->employee_name,
                    $contract->department,
                    $daysLeft,
                    $contract->end_date->format('Y-m-d')
                );

                $this->line($message);
                Log::warning('Contract Expiring Soon: ' . $message);
            }

            $this->newLine();
            $this->info('Expiring contracts have been logged.');
        } else {
            $this->info('No contracts expiring within 30 days.');
        }

        return Command::SUCCESS;
    }
}
