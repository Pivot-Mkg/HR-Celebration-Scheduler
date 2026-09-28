<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('email_templates')->nullOnDelete();
            $table->enum('event_type', ['birthday', 'anniversary']);
            $table->date('event_date');
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->string('to_email');
            $table->string('cc_email')->nullable();
            $table->string('bcc_email')->nullable();
            $table->string('subject')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed', 'skipped'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index('employee_id');
            $table->index('event_type');
            $table->index('event_date');
            $table->index('status');
            // Unique: one log entry per employee per event per day
            $table->unique(['employee_id', 'event_type', 'event_date'], 'unique_employee_event_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
