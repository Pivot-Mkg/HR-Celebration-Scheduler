<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique();
            $table->string('employee_name');
            $table->string('email')->unique();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->string('manager_name')->nullable();
            $table->string('manager_email')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('employee_code');
            $table->index('email');
            $table->index('status');
            $table->index('date_of_birth');
            $table->index('date_of_joining');
            $table->index('department');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
