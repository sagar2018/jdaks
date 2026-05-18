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
        Schema::create('pv_indices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('month', 7); // YYYY-MM
            $table->decimal('labour_idx', 10, 4)->default(0);
            $table->decimal('cement_idx', 10, 4)->default(0);
            $table->decimal('steel_idx', 10, 4)->default(0);
            $table->decimal('bitumen_idx', 10, 4)->default(0);
            $table->decimal('pol_idx', 10, 4)->default(0);
            $table->decimal('other_idx', 10, 4)->default(0);
            $table->decimal('plant_idx', 10, 4)->default(0);
            $table->string('base_month', 7)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['project_id', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pv_indices');
    }
};
