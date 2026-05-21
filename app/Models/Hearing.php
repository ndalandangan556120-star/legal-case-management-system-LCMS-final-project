<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hearing extends Model
{
    use HasFactory;

    protected $fillable = ['case_id', 'hearing_date', 'location', 'notes', 'status'];

    public function legalCase()
    {
        return $this->belongsTo(LegalCase::class, 'case_id');
    }
}
