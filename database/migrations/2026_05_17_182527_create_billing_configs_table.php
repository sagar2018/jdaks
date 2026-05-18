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
        Schema::create('billing_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('loa_number')->nullable();
            $table->decimal('tendered_value', 18, 2)->default(0);
            $table->enum('bid_type', ['below', 'at_par', 'above'])->default('at_par');
            $table->decimal('bid_pct', 5, 2)->default(0);
            $table->decimal('sd_pct', 5, 2)->default(5);
            $table->decimal('it_tds_pct', 5, 2)->default(1);
            $table->decimal('labour_cess_pct', 5, 2)->default(1);
            $table->decimal('gst_tds_pct', 5, 2)->default(2);
            $table->timestamp('configured_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_configs');
    }
};
