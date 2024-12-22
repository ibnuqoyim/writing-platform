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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('assistant_id')->constrained()->onDelete('cascade');
            $table->string('thread_id')->nullable();
            $table->string('theme')->nullable();
            $table->string('field_study')->nullable();
            $table->string('research_type')->nullable();
            $table->string('title')->nullable();
            $table->string('research_method')->nullable();
            $table->text('abstract')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
