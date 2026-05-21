<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['case_id', 'name', 'file_path', 'file_type'];

    public function legalCase()
    {
        return $this->belongsTo(LegalCase::class, 'case_id');
    }
}
