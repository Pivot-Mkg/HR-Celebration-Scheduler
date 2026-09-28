<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_code',
        'employee_name',
        'email',
        'department',
        'designation',
        'date_of_birth',
        'date_of_joining',
        'manager_name',
        'manager_email',
        'status',
    ];

    protected $casts = [
        'date_of_birth'  => 'date',
        'date_of_joining' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('employee_name', 'like', "%{$search}%")
              ->orWhere('employee_code', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('department', 'like', "%{$search}%");
        });
    }

    public function getNextBirthdayAttribute(): ?Carbon
    {
        if (!$this->date_of_birth) {
            return null;
        }
        $today = Carbon::today();
        $next = $this->date_of_birth->copy()->year($today->year);
        if ($next->lt($today)) {
            $next->addYear();
        }
        return $next;
    }

    public function getNextAnniversaryAttribute(): ?Carbon
    {
        if (!$this->date_of_joining) {
            return null;
        }
        $today = Carbon::today();
        $next = $this->date_of_joining->copy()->year($today->year);
        if ($next->lt($today)) {
            $next->addYear();
        }
        return $next;
    }

    public function getCompletedYearsAttribute(): ?int
    {
        if (!$this->date_of_joining) {
            return null;
        }
        return (int) $this->date_of_joining->diffInYears(Carbon::today());
    }

    public function isBirthdayToday(): bool
    {
        if (!$this->date_of_birth) {
            return false;
        }
        $today = Carbon::today();
        return $this->date_of_birth->month === $today->month
            && $this->date_of_birth->day === $today->day;
    }

    public function isAnniversaryToday(): bool
    {
        if (!$this->date_of_joining) {
            return false;
        }
        $today = Carbon::today();
        return $this->date_of_joining->month === $today->month
            && $this->date_of_joining->day === $today->day;
    }
}
