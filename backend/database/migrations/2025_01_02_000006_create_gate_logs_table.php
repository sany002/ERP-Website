<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gate_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            // Fallback for walk-in / unregistered vehicles at the gate
            $table->string('registration_number')->nullable();
            $table->foreignId('job_card_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('direction', ['in', 'out'])->default('in');
            $table->foreignId('gate_operator_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('odometer_reading')->nullable();
            $table->string('driver_name')->nullable();
            $table->text('remarks')->nullable();
            $table->string('photo')->nullable(); // vehicle condition photo at gate
            $table->timestamp('logged_at')->useCurrent();
            $table->timestamps();

            $table->index(['company_id', 'branch_id', 'direction']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gate_logs');
    }
};
