<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContractHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
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
        'action_type',
        'notes',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the contract that owns this history record
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
