<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_name',
        'nik',
        'birthplace',
        'birthdate',
        'address',
        'nomor_hp',
        'file_cv',
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    /**
     * Get all contracts for this employee
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Get the latest contract for this employee
     */
    public function latestContract()
    {
        return $this->hasOne(Contract::class)->latestOfMany();
    }
}
