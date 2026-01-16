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
        'nid',
        'birthplace',
        'birthdate',
        'TMT_awal',
        'address',
        'nomor_hp',
        'file_cv',
        'status',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'TMT_awal' => 'date',
    ];

    /**
     * Get all contracts for this employee
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    /**     * Get the layoff record for this employee
     */
    public function layoff()
    {
        return $this->hasOne(Layoff::class);
    }

    /**
     * Check if employee has been laid off
     */
    public function isLaidOff()
    {
        return $this->layoff()->exists();
    }

    /**     * Get the latest contract for this employee
     */
    public function latestContract()
    {
        return $this->hasOne(Contract::class)->latestOfMany();
    }
}
