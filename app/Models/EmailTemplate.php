<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class EmailTemplate extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'name', 'event_type', 'subject', 'body_html', 'body_text',
        'from_email', 'from_name', 'cc_addresses', 'bcc_addresses',
        'is_active', 'is_default', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'is_default' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForEvent($query, string $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    public static function getDefault(string $eventType): ?self
    {
        return static::active()
            ->forEvent($eventType)
            ->where('is_default', true)
            ->first();
    }

    public function setAsDefault(): void
    {
        DB::transaction(function () {
            static::where('event_type', $this->event_type)
                  ->where('id', '!=', $this->id)
                  ->update(['is_default' => false]);

            $this->update(['is_default' => true, 'is_active' => true]);
        });
    }

    public function duplicate(): self
    {
        $clone = $this->replicate();
        $clone->name = $this->name . ' (Copy)';
        $clone->is_default = false;
        $clone->save();
        return $clone;
    }
}
