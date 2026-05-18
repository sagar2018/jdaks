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
        Schema::create('inventory_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('material_code', 30)->nullable();
            $table->string('name');
            $table->string('unit', 20);
            $table->decimal('reorder_qty', 15, 3)->default(0);
            $table->decimal('opening_stock', 15, 3)->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['project_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_materials');
    }
};
