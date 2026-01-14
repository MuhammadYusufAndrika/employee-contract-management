<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'nomor_kontrak',
        'job_position',
        'point_of_hire',
        'TMT_awal',
        'contract_type',
        'start_date',
        'end_date',
        'department',
        'work_location',
        'file_contract',
    ];

    protected $casts = [
        'TMT_awal' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the employee that owns the contract
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get contracts expiring within the specified number of days
     */
    public static function expiringWithinDays(int $days = 30)
    {
        $today = Carbon::today();
        $futureDate = Carbon::today()->addDays($days);

        return static::with('employee')
            ->whereBetween('end_date', [$today, $futureDate])
            ->orderBy('end_date', 'asc')
            ->get();
    }

    /**
     * Check if contract is expiring soon (within 30 days)
     */
    public function isExpiringSoon(): bool
    {
        if (!$this->end_date) {
            return false; // Permanent contracts don't expire
        }

        $today = Carbon::today();
        $thirtyDaysFromNow = Carbon::today()->addDays(30);

        return $this->end_date >= $today && $this->end_date <= $thirtyDaysFromNow;
    }

    /**
     * Get days until contract expiration
     */
    public function daysUntilExpiration(): int
    {
        if (!$this->end_date) {
            return 0; // Permanent contract
        }

        return Carbon::today()->diffInDays($this->end_date, false);
    }

    /**
     * Get all history records for this contract
     */
    public function histories()
    {
        return $this->hasMany(ContractHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Create a history record for this contract
     */
    public function createHistory(string $actionType, ?string $notes = null)
    {
        return $this->histories()->create([
            'employee_name' => $this->employee->employee_name ?? 'N/A',
            'nik' => $this->employee->nik ?? 'N/A',
            'nomor_kontrak' => $this->nomor_kontrak,
            'birthdate' => $this->employee->birthdate ?? null,
            'birthplace' => $this->employee->birthplace ?? 'N/A',
            'address' => $this->employee->address ?? 'N/A',
            'job_position' => $this->job_position,
            'contract_type' => $this->contract_type,
            'start_date' => $this->start_date,
            'point_of_hire' => $this->point_of_hire,
            'end_date' => $this->end_date,
            'department' => $this->department,
            'work_location' => $this->work_location,
            'file_contract' => $this->file_contract,
            'action_type' => $actionType,
            'notes' => $notes,
        ]);
    }
}
