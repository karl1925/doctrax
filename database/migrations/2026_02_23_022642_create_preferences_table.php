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
        Schema::create('preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('external_email_notify_received')->default(true);
            $table->boolean('external_email_notify_updated')->default(true);
            $table->boolean('external_email_notify_completed')->default(true);
            $table->boolean('internal_email_notify_received')->default(true);
            $table->boolean('internal_email_notify_returned')->default(true);
            $table->boolean('internal_email_notify_reviewed')->default(true);
            $table->boolean('internal_email_notify_completed')->default(true);
            $table->boolean('internal_email_notify_rejected')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preferences');
    }
};
