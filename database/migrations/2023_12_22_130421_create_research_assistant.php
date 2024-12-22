<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Assistant;

class CreateResearchAssistant extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the research assistant if it doesn't exist
        if (!Assistant::where('type', 'research')->exists()) {
            Assistant::create([
                'name' => 'Research Assistant',
                'type' => 'research',
                'description' => 'AI assistant specialized in research paper writing',
                'instructions' => 'You are a research paper writing assistant. Help users develop their research papers by providing guidance, feedback, and suggestions.',
                'model' => 'gpt-4-1106-preview'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Assistant::where('type', 'research')->delete();
    }
}
