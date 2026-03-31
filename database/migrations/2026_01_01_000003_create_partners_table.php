<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique(); // partner code
            $table->string('email')->nullable();
            $table->string('contactNo')->nullable();
            $table->enum('type', ['NGA', 'LGU', 'SUC', 'NGO', 'Others'])->default('Others');
            $table->timestamps();
            $table->softDeletes(); // adds deleted_at column for soft deletes
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
