<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('computers', function (Blueprint $table) {
            $table->id(); $table->string('pc_number', 20)->unique(); $table->string('computer_name')->nullable();
            $table->string('station_token_hash', 64)->unique(); $table->boolean('active')->default(true);
            $table->timestamp('last_seen_at')->nullable(); $table->timestamps();
        });
        Schema::create('grade_levels', function (Blueprint $table) { $table->id(); $table->string('name')->unique(); $table->boolean('active')->default(true); $table->timestamps(); });
        Schema::create('subjects', function (Blueprint $table) { $table->id(); $table->string('name')->unique(); $table->boolean('active')->default(true); $table->timestamps(); });
        Schema::create('usage_reasons', function (Blueprint $table) { $table->id(); $table->string('name')->unique(); $table->boolean('active')->default(true); $table->timestamps(); });
        Schema::create('laboratory_sessions', function (Blueprint $table) {
            $table->id(); $table->foreignId('computer_id')->constrained()->restrictOnDelete();
            $table->string('first_name'); $table->string('last_name'); $table->string('student_id')->nullable();
            $table->foreignId('grade_level_id')->constrained()->restrictOnDelete(); $table->foreignId('subject_id')->constrained()->restrictOnDelete();
            $table->foreignId('usage_reason_id')->constrained()->restrictOnDelete(); $table->string('other_reason_details')->nullable();
            $table->timestamp('time_in'); $table->timestamp('time_out')->nullable(); $table->unsignedInteger('duration_seconds')->nullable();
            $table->enum('status', ['ACTIVE','COMPLETED','INTERRUPTED'])->default('ACTIVE'); $table->string('resolution')->nullable(); $table->timestamps();
            $table->index(['status','time_in']); $table->index(['student_id','time_in']); $table->index(['computer_id','status']);
        });
        Schema::create('audit_logs', function (Blueprint $table) { $table->id(); $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); $table->string('action'); $table->string('subject_type')->nullable(); $table->unsignedBigInteger('subject_id')->nullable(); $table->json('details')->nullable(); $table->string('ip_address',45)->nullable(); $table->timestamps(); });
        Schema::create('application_settings', function (Blueprint $table) { $table->id(); $table->string('key')->unique(); $table->text('value')->nullable(); $table->timestamps(); });
    }
    public function down(): void { Schema::dropIfExists('application_settings'); Schema::dropIfExists('audit_logs'); Schema::dropIfExists('laboratory_sessions'); Schema::dropIfExists('usage_reasons'); Schema::dropIfExists('subjects'); Schema::dropIfExists('grade_levels'); Schema::dropIfExists('computers'); }
};
