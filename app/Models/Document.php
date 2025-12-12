<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'document_number',
        'document_type',
        'theme',
        'description',
        'enacted_date',
        'published_date',
        'file_path',
        'is_active',
        'download_count',
    ];

    protected $casts = [
        'enacted_date' => 'date',
        'published_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }
}
