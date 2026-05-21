<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalCase extends Model
{
    use HasFactory;

    public const STATUS_OPEN = 'Open';
    public const STATUS_IN_PROGRESS = 'In Progress';
    public const STATUS_SCHEDULED = 'Scheduled';
    public const STATUS_CLOSED = 'Closed';

    protected $table = 'cases';

    protected $fillable = [
        'case_number', 'title', 'description', 'incident_date', 
        'status', 'client_id', 'lawyer_id'
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_OPEN,
            self::STATUS_IN_PROGRESS,
            self::STATUS_SCHEDULED,
            self::STATUS_CLOSED,
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    public function hearings()
    {
        return $this->hasMany(Hearing::class, 'case_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'case_id');
    }

    public function getCaseTypeAttribute(): string
    {
        $prefix = strtoupper(substr($this->case_number, 0, 2));

        return match ($prefix) {
            'CC' => 'Criminal Case',
            'CV' => 'Civil Case',
            'FC' => 'Family Case',
            default => 'General Case',
        };
    }
}
