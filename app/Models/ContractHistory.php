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
        'nomor_kontrak',
        'birthdate',
        'birthplace',
        'address',
        'job_position',
        'point_of_hire',
        'contract_type',
        'start_date',
        'end_date',
        'department',
        'work_location',
        'file_contract',
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
