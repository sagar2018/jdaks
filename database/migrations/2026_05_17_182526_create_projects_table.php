<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('package')->nullable();
            $table->string('location')->nullable();
            $table->string('contractor')->nullable();
            $table->decimal('chainage_start', 10, 3)->nullable();
            $table->decimal('chainage_end', 10, 3)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('deadline')->nullable();
            $table->date('bid_date')->nullable();
            $table->enum('bid_type', ['below', 'at_par', 'above'])->default('at_par');
            $table->decimal('bid_pct', 5, 2)->default(0);
            $table->enum('bit_grade', ['VG-30', 'VG-40'])->default('VG-30');
            $table->boolean('dual_lane')->default(false);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
