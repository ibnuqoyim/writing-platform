<?php

namespace App\Http\Controllers;

use App\Models\Assistant;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use OpenAI\Exceptions\ErrorException;

class AssistantController extends Controller
{
    public function index()
    {
        $assistants = Assistant::all();
        return view('assistants.index', compact('assistants'));
    }

    public function apiIndex()
    {
        $assistants = Assistant::all();
        return response()->json($assistants);
    }

    public function store(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'model' => 'required|string|in:gpt-4,gpt-3.5-turbo',
                'description' => 'required|string',
                'instructions' => 'required|string',
            ]);

            // Check OpenAI API key
            if (empty(config('openai.api_key'))) {
                Log::error('OpenAI API key is not configured');
                return response()->json([
                    'error' => 'OpenAI API key is not configured. Please check your .env file.'
                ], 500);
            }

            // Create OpenAI Assistant
            try {
                $openaiAssistant = OpenAI::assistants()->create([
                    'name' => $validated['name'],
                    'model' => $validated['model'],
                    'description' => $validated['description'],
                    'instructions' => $validated['instructions'],
                ]);
            } catch (ErrorException $e) {
                Log::error('OpenAI API Error: ' . $e->getMessage());
                return response()->json([
                    'error' => 'Failed to create OpenAI Assistant: ' . $e->getMessage()
                ], 500);
            }

            // Create local Assistant record
            $assistant = Assistant::create([
                'name' => $validated['name'],
                'model' => $validated['model'],
                'description' => $validated['description'],
                'instructions' => $validated['instructions'],
                'openai_assistant_id' => $openaiAssistant->id,
                'status' => 'active',
            ]);

            Log::info('Assistant created successfully', ['id' => $assistant->id]);
            return response()->json($assistant, 201);

        } catch (\Exception $e) {
            Log::error('Error creating assistant: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to create assistant: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Assistant $assistant)
    {
        return response()->json($assistant);
    }

    public function update(Request $request, Assistant $assistant)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'model' => 'required|string|in:gpt-4,gpt-3.5-turbo',
                'description' => 'required|string',
                'instructions' => 'required|string',
            ]);

            // Update OpenAI Assistant
            try {
                OpenAI::assistants()->update($assistant->openai_assistant_id, [
                    'name' => $validated['name'],
                    'model' => $validated['model'],
                    'description' => $validated['description'],
                    'instructions' => $validated['instructions'],
                ]);
            } catch (ErrorException $e) {
                Log::error('OpenAI API Error: ' . $e->getMessage());
                return response()->json([
                    'error' => 'Failed to update OpenAI Assistant: ' . $e->getMessage()
                ], 500);
            }

            // Update local Assistant record
            $assistant->update($validated);

            Log::info('Assistant updated successfully', ['id' => $assistant->id]);
            return response()->json($assistant);

        } catch (\Exception $e) {
            Log::error('Error updating assistant: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to update assistant: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Assistant $assistant)
    {
        try {
            // Delete OpenAI Assistant
            try {
                OpenAI::assistants()->delete($assistant->openai_assistant_id);
            } catch (ErrorException $e) {
                Log::error('OpenAI API Error: ' . $e->getMessage());
                return response()->json([
                    'error' => 'Failed to delete OpenAI Assistant: ' . $e->getMessage()
                ], 500);
            }

            // Delete local record
            $assistant->delete();

            Log::info('Assistant deleted successfully', ['id' => $assistant->id]);
            return response()->json(['message' => 'Assistant deleted successfully']);

        } catch (\Exception $e) {
            Log::error('Error deleting assistant: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to delete assistant: ' . $e->getMessage()
            ], 500);
        }
    }
}
