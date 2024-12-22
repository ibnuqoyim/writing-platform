<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function show(Task $task)
    {
        // Check if user has permission to view this task
        if (auth()->user()->role !== 'admin' && auth()->id() !== $task->user_id) {
            abort(403, 'Unauthorized');
        }

        // Get all messages in the thread
        $messages = OpenAI::threads()->messages()->list($task->thread_id);

        return view('chat.show', compact('task', 'messages'));
    }

    public function sendMessage(Request $request, Task $task)
    {
        // Check if user has permission to chat in this task
        if (auth()->user()->role !== 'admin' && auth()->id() !== $task->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Validate request
            $validated = $request->validate([
                'message' => 'required|string',
            ]);

            Log::info('Creating message in thread', [
                'thread_id' => $task->thread_id,
                'message' => $validated['message']
            ]);

            // Create message in thread
            $message = OpenAI::threads()->messages()->create($task->thread_id, [
                'role' => 'user',
                'content' => $validated['message'],
            ]);

            Log::info('Creating run with assistant', [
                'thread_id' => $task->thread_id,
                'assistant_id' => $task->assistant->openai_assistant_id
            ]);

            // Create run with the assistant
            $run = OpenAI::threads()->runs()->create($task->thread_id, [
                'assistant_id' => $task->assistant->openai_assistant_id,
            ]);

            // Wait for the run to complete
            $maxAttempts = 60; // Increase timeout to 60 seconds
            $attempts = 0;
            do {
                sleep(1); // Wait 1 second before checking again
                $runStatus = OpenAI::threads()->runs()->retrieve($task->thread_id, $run->id);
                $attempts++;

                Log::info('Run status check', [
                    'attempt' => $attempts,
                    'status' => $runStatus->status,
                    'run_id' => $run->id
                ]);

            } while ($runStatus->status === 'in_progress' && $attempts < $maxAttempts);

            if ($runStatus->status === 'completed') {
                // Get the assistant's response
                $messages = OpenAI::threads()->messages()->list($task->thread_id);
                
                Log::info('Run completed successfully', [
                    'run_id' => $run->id,
                    'message_count' => count($messages->data)
                ]);

                return response()->json([
                    'messages' => $messages->data
                ]);
            } else {
                Log::error('Run did not complete successfully', [
                    'status' => $runStatus->status,
                    'run_id' => $run->id
                ]);

                throw new \Exception('Run failed with status: ' . $runStatus->status);
            }

        } catch (\Exception $e) {
            Log::error('Error in chat', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Failed to process message: ' . $e->getMessage()
            ], 500);
        }
    }
}
