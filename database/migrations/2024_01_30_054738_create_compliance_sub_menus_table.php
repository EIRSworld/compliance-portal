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
        Schema::create('compliance_sub_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->nullable();
            $table->foreignId('compliance_menu_id')->nullable();
            $table->foreignId('country_id')->nullable();
            $table->foreignId('entity_id')->nullable();
            $table->foreignId('calendar_year_id')->nullable();
            $table->string('year')->nullable();
            $table->string('sub_menu_name')->nullable();
            $table->boolean('status')->nullable()->default(1);
            $table->auditTrail();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_sub_menus');
    }
};
