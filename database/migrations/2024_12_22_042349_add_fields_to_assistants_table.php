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
            if (!Schema::hasColumn('assistants', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('assistants', 'status')) {
                $table->string('status')->default('active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assistants', function (Blueprint $table) {
            $table->dropColumn(['description', 'status']);
        });
    }
};
