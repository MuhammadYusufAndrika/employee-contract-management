<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Layoff extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'layoff_date',
        'reason',
        'layoff_letter',
        'processed_by',
    ];

    protected $casts = [
        'layoff_date' => 'date',
    ];

    /**
     * Get the employee that was laid off
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the user who processed the layoff
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
