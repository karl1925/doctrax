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
        Schema::create('externals', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->string('agency');
            $table->string('contact');
            $table->text('description')->nullable();
            $table->text('reference')->nullable();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->enum('division', ['AFD', 'TOD']);
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('priority', ['normal', 'high', 'urgent'])->default('normal');
            $table->timestamp('target_date')->nullable();
            $table->enum('status', ['pending', 'assigned', 'accepted', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('externals');
    }
};
