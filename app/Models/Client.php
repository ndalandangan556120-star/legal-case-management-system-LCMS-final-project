<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['full_name', 'email', 'phone', 'address', 'user_id', 'lawyer_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    public function cases()
    {
        return $this->hasMany(LegalCase::class);
    }

    public function getClientTypeAttribute(): string
    {
        $name = strtolower($this->full_name);
        $email = strtolower($this->email);

        if (Str::contains($name, ['llc', 'inc', 'corp', 'ltd', 'company', 'co.']) || Str::contains($email, ['@corp', '@company', '@llc', '@lawfirm'])) {
            return 'Corporate';
        }

        if (Str::contains($name, ['law', 'attorneys', 'llp', 'legal', 'partners'])) {
            return 'Law Firm';
        }

        if (Str::contains($name, ['group', 'partners', 'associates']) && !Str::contains($name, ['law'])) {
            return 'Other';
        }

        return 'Individual';
    }
}
