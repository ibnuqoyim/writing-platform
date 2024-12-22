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
        Schema::table('assistants', function (Blueprint $table) {
            if (!Schema::hasColumn('assistants', 'assistant_id')) {
                $table->string('assistant_id')->nullable()->unique();
            }
            if (!Schema::hasColumn('assistants', 'instructions')) {
                $table->text('instructions')->nullable();
            }
            if (!Schema::hasColumn('assistants', 'model')) {
                $table->string('model')->default('gpt-4');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assistants', function (Blueprint $table) {
            $table->dropColumn(['assistant_id', 'instructions', 'model']);
        });
    }
};
