<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'is_sensitive'];

    protected $casts = [
        'is_sensitive' => 'boolean',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, mixed $value, bool $isSensitive = false): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'is_sensitive' => $isSensitive]
        );
    }

    /**
     * Returns application-level mail settings.
     * SMTP transport (host, port, username, password, encryption) is always
     * read from .env / config/mail.php — never stored in the database.
     * Only from_address, from_name, cc, bcc, and test_email are DB-managed.
     *
     * The is_sensitive column exists for future use (e.g. API keys stored via
     * this same settings system). No value currently stored here is sensitive.
     */
    public static function getMailConfig(): array
    {
        return [
            'from_address' => static::get('mail_from_address', config('mail.from.address')),
            'from_name'    => static::get('mail_from_name',    config('mail.from.name')),
            'cc'           => static::get('mail_default_cc',   ''),
            'bcc'          => static::get('mail_default_bcc',  ''),
            'test_email'   => static::get('mail_test_address', ''),
        ];
    }
}
