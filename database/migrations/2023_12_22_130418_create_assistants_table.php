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
        if (!Schema::hasTable('assistants')) {
            Schema::create('assistants', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type');
                $table->text('description');
                $table->text('instructions');
                $table->string('model');
                $table->string('openai_assistant_id')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistants');
    }
};
