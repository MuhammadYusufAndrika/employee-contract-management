<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Contract extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_name',
        'nik',
        'birthdate',
        'birthplace',
        'address',
        'job_position',
        'start_date',
        'end_date',
        'department',
        'work_location',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get contracts expiring within the specified number of days
     */
    public static function expiringWithinDays(int $days = 30)
    {
        $today = Carbon::today();
        $futureDate = Carbon::today()->addDays($days);

        return static::whereBetween('end_date', [$today, $futureDate])
            ->orderBy('end_date', 'asc')
            ->get();
    }

    /**
     * Check if contract is expiring soon (within 30 days)
     */
    public function isExpiringSoon(): bool
    {
        $today = Carbon::today();
        $thirtyDaysFromNow = Carbon::today()->addDays(30);

        return $this->end_date >= $today && $this->end_date <= $thirtyDaysFromNow;
    }

    /**
     * Get days until contract expiration
     */
    public function daysUntilExpiration(): int
    {
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
            'employee_name' => $this->employee_name,
            'nik' => $this->nik,
            'birthdate' => $this->birthdate,
            'birthplace' => $this->birthplace,
            'address' => $this->address,
            'job_position' => $this->job_position,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'department' => $this->department,
            'work_location' => $this->work_location,
            'action_type' => $actionType,
            'notes' => $notes,
        ]);
    }
}
