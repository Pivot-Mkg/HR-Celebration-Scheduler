<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE email_templates MODIFY COLUMN event_type ENUM('birthday','anniversary','founding_day') NOT NULL");
            DB::statement("ALTER TABLE email_logs MODIFY COLUMN event_type ENUM('birthday','anniversary','founding_day') NOT NULL");
            return;
        }

        // SQLite: recreate email_templates with updated CHECK constraint
        Schema::create('email_templates_new', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('event_type'); // plain string — CHECK removed; app validates
            $table->string('subject');
            $table->text('body_html');
            $table->text('body_text')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->string('cc_addresses')->nullable();
            $table->string('bcc_addresses')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('INSERT INTO email_templates_new SELECT * FROM email_templates');
        Schema::drop('email_templates');
        DB::statement('ALTER TABLE email_templates_new RENAME TO email_templates');

        // Same for email_logs
        Schema::create('email_logs_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('email_templates')->nullOnDelete();
            $table->string('event_type');
            $table->date('event_date');
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->string('to_email')->nullable();
            $table->string('cc_email')->nullable();
            $table->string('bcc_email')->nullable();
            $table->string('subject')->nullable();
            $table->string('status', 20)->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        DB::statement('INSERT INTO email_logs_new SELECT * FROM email_logs');
        Schema::drop('email_logs');
        DB::statement('ALTER TABLE email_logs_new RENAME TO email_logs');
    }

    public function down(): void
    {
        // Not reversible in a meaningful way for SQLite; MySQL restores the enum
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE email_templates MODIFY COLUMN event_type ENUM('birthday','anniversary') NOT NULL");
            DB::statement("ALTER TABLE email_logs MODIFY COLUMN event_type ENUM('birthday','anniversary') NOT NULL");
        }
    }
};
