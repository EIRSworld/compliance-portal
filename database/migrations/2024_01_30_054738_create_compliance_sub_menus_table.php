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
            $table->foreignId('country_id')->nullable();
            $table->foreignId('compliance_menu_id')->nullable();
            $table->string('name')->nullable();
            $table->date('renewed_date')->nullable();
            $table->date('expired_date')->nullable();
            $table->boolean('is_uploaded')->nullable()->default(0);
            $table->boolean('approve_status')->nullable()->default(0);
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
