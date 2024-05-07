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
        Schema::create('compliance_primary_sub_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->nullable();
            $table->foreignId('compliance_menu_id')->nullable();
            $table->foreignId('compliance_sub_menu_id')->nullable();
            $table->foreignId('country_id')->nullable();
            $table->foreignId('entity_id')->nullable();
            $table->foreignId('calendar_year_id')->nullable();
            $table->foreignId('assign_id')->nullable();
            $table->string('year')->nullable();
            $table->string('occurrence')->nullable();
            $table->string('event_name')->nullable();
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->boolean('is_uploaded')->nullable()->default(0);
            $table->foreignId('upload_by')->nullable();
            $table->date('upload_date')->nullable();
            $table->text('upload_comment')->nullable();
            $table->boolean('approve_status')->nullable()->default(0);
            $table->foreignId('approve_by')->nullable();
            $table->date('approve_date')->nullable();
            $table->text('reject_comment')->nullable();
            $table->string('status')->nullable();
            $table->string('status_text')->nullable();
            $table->auditTrail();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_primary_sub_menus');
    }
};
