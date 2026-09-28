<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'template_id', 'event_type', 'event_date',
        'from_email', 'from_name', 'to_email', 'cc_email', 'bcc_email',
        'subject', 'status', 'error_message', 'sent_at',
    ];

    protected $casts = [
        'event_date' => 'date',
        'sent_at'    => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class);
    }

    public static function alreadySent(int $employeeId, string $eventType, string $eventDate): bool
    {
        return static::where('employee_id', $employeeId)
                     ->where('event_type', $eventType)
                     ->whereDate('event_date', $eventDate)
                     ->whereIn('status', ['sent', 'skipped'])
                     ->exists();
    }

    public function scopeFiltered($query, array $filters)
    {
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['event_type'])) {
            $query->where('event_type', $filters['event_type']);
        }
        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('event_date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('event_date', '<=', $filters['date_to']);
        }
        return $query;
    }
}
