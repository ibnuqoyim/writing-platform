<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Assistant;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())->latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $assistants = Assistant::all();
        return view('tasks.create', compact('assistants'));
    }

    public function store(Request $request)
    {
        // Enable query logging
        DB::enableQueryLog();

        $validated = $request->validate([
            'theme' => 'required|string|max:255',
            'field_study' => 'required|string|max:255',
            'research_type' => 'required|string|in:' . implode(',', array_keys(Task::$researchTypes)),
            'title' => 'required|string|max:255',
            'research_method' => 'required|string|in:' . implode(',', array_keys(Task::$researchMethods)),
            'abstract' => 'required|string|max:2000',
            'assistant_id' => 'required|exists:assistants,id',
        ]);

        try {
            // Get the selected assistant
            $assistant = Assistant::findOrFail($validated['assistant_id']);

            // Create a new thread
            $thread = OpenAI::threads()->create();

            // Create initial message with research details
            $initialMessage = "I am working on a research paper with the following details:\n\n" .
                            "Theme: {$validated['theme']}\n" .
                            "Field of Study: {$validated['field_study']}\n" .
                            "Research Type: " . Task::$researchTypes[$validated['research_type']] . "\n" .
                            "Title: {$validated['title']}\n" .
                            "Research Method: " . Task::$researchMethods[$validated['research_method']] . "\n\n" .
                            "Abstract:\n{$validated['abstract']}\n\n" .
                            "Please help me develop this research paper. Let's start by discussing the approach and outline.";

            // Create the message in the thread
            OpenAI::threads()->messages()->create($thread->id, [
                'role' => 'user',
                'content' => $initialMessage,
            ]);

            // Create the task
            $task = Task::create([
                'user_id' => auth()->id(),
                'assistant_id' => $assistant->id,
                'thread_id' => $thread->id,
                'theme' => $validated['theme'],
                'field_study' => $validated['field_study'],
                'research_type' => $validated['research_type'],
                'title' => $validated['title'],
                'research_method' => $validated['research_method'],
                'abstract' => $validated['abstract'],
                'status' => 'draft'
            ]);

            // Log the queries
            Log::info('SQL Queries:', DB::getQueryLog());

            return redirect()->route('chat.show', $task)
                           ->with('success', 'Research task created successfully! Let\'s start working on your paper.');

        } catch (\Exception $e) {
            // Log the queries in case of error
            Log::error('SQL Queries:', DB::getQueryLog());
            Log::error('Error creating task: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create task. Error: ' . $e->getMessage());
        }
    }

    public function show(Task $task)
    {
        if (auth()->user()->role !== 'admin' && auth()->id() !== $task->user_id) {
            abort(403, 'Unauthorized');
        }

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        if (auth()->user()->role !== 'admin' && auth()->id() !== $task->user_id) {
            abort(403, 'Unauthorized');
        }

        $assistants = Assistant::all();
        return view('tasks.edit', compact('task', 'assistants'));
    }

    public function update(Request $request, Task $task)
    {
        if (auth()->user()->role !== 'admin' && auth()->id() !== $task->user_id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'theme' => 'required|string|max:255',
            'field_study' => 'required|string|max:255',
            'research_type' => 'required|string|in:' . implode(',', array_keys(Task::$researchTypes)),
            'title' => 'required|string|max:255',
            'research_method' => 'required|string|in:' . implode(',', array_keys(Task::$researchMethods)),
            'abstract' => 'required|string|max:2000',
            'status' => 'required|string|in:' . implode(',', array_keys(Task::$statuses)),
            'assistant_id' => 'required|exists:assistants,id',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.show', $task)
                        ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        if (auth()->user()->role !== 'admin' && auth()->id() !== $task->user_id) {
            abort(403, 'Unauthorized');
        }

        try {
            if ($task->thread_id) {
                OpenAI::threads()->delete($task->thread_id);
            }
            $task->delete();
            return redirect()->route('tasks.index')
                           ->with('success', 'Task deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting task: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete task. Error: ' . $e->getMessage());
        }
    }
}
