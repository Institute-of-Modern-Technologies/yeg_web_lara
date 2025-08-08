<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChallengeQuestionController extends Controller
{
    /**
     * Display a listing of the challenge questions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = \App\Models\ChallengeQuestion::with('category');
        
        // Filter by category if provided
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filter by difficulty level if provided
        if ($request->has('difficulty') && $request->difficulty) {
            $query->where('difficulty', $request->difficulty);
        }
        
        // Search by question content if provided
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer_a', 'like', "%{$search}%")
                  ->orWhere('answer_b', 'like', "%{$search}%")
                  ->orWhere('answer_c', 'like', "%{$search}%")
                  ->orWhere('answer_d', 'like', "%{$search}%");
            });
        }
        
        $questions = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = \App\Models\ChallengeCategory::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.challenges.questions.index', compact('questions', 'categories'));
    }

    /**
     * Show the form for creating a new challenge question.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = \App\Models\ChallengeCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.challenges.questions.create', compact('categories'));
    }

    /**
     * Store a newly created challenge question.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:challenge_categories,id',
            'question' => 'required|string',
            'answer_a' => 'required|string',
            'answer_b' => 'required|string',
            'answer_c' => 'required|string',
            'answer_d' => 'required|string',
            'correct_answer' => 'required|in:A,B,C,D',
            'difficulty' => 'required|in:easy,medium,hard',
            'time_allowed' => 'required|integer|min:10|max:120',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);
        
        // Set is_active to true if not present
        $validatedData['is_active'] = $validatedData['is_active'] ?? true;
        
        $question = \App\Models\ChallengeQuestion::create($validatedData);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Question created successfully!',
                'question' => $question->load('category')
            ]);
        }
        
        return redirect()->route('admin.challenges.questions.index')
            ->with('success', 'Question created successfully!');
    }

    /**
     * Show the form for editing the specified challenge question.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = \App\Models\ChallengeQuestion::findOrFail($id);
        $categories = \App\Models\ChallengeCategory::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.challenges.questions.edit', compact('question', 'categories'));
    }

    /**
     * Update the specified challenge question.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $question = \App\Models\ChallengeQuestion::findOrFail($id);
        
        $validatedData = $request->validate([
            'category_id' => 'required|exists:challenge_categories,id',
            'question' => 'required|string',
            'answer_a' => 'required|string',
            'answer_b' => 'required|string',
            'answer_c' => 'required|string',
            'answer_d' => 'required|string',
            'correct_answer' => 'required|in:A,B,C,D',
            'difficulty' => 'required|in:easy,medium,hard',
            'time_allowed' => 'required|integer|min:10|max:120',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);
        
        // Set is_active to false if not present
        $validatedData['is_active'] = $validatedData['is_active'] ?? false;
        
        $question->update($validatedData);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Question updated successfully!',
                'question' => $question->fresh()->load('category')
            ]);
        }
        
        return redirect()->route('admin.challenges.questions.index')
            ->with('success', 'Question updated successfully!');
    }

    /**
     * Remove the specified challenge question.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = \App\Models\ChallengeQuestion::findOrFail($id);
        
        // Check if question is used in any challenge
        if ($question->challenges()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete question that is being used in challenges.'
            ], 422);
        }
        
        $question->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Question deleted successfully!'
        ]);
    }
    
    /**
     * Bulk import questions from CSV file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120', // 5MB max
        ]);
        
        $file = $request->file('file');
        $path = $file->getRealPath();
        
        $handle = fopen($path, 'r');
        $header = fgetcsv($handle, 1000, ',');
        
        // Expected headers
        $expectedHeaders = [
            'category_id', 'question', 'answer_a', 'answer_b', 'answer_c', 'answer_d', 
            'correct_answer', 'difficulty', 'time_allowed', 'points', 'explanation'
        ];
        
        // Check if headers match expected format
        $missingHeaders = array_diff($expectedHeaders, $header);
        if (!empty($missingHeaders)) {
            return redirect()->back()->with('error', 'CSV file is missing required columns: ' . implode(', ', $missingHeaders));
        }
        
        $success = 0;
        $failed = 0;
        $errors = [];
        
        // Skip header row and process data
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $rowData = array_combine($header, $data);
            
            // Validate category exists
            if (!\App\Models\ChallengeCategory::where('id', $rowData['category_id'])->exists()) {
                $failed++;
                $errors[] = "Row with question '{$rowData['question']}' has invalid category ID.";
                continue;
            }
            
            // Validate correct answer
            if (!in_array($rowData['correct_answer'], ['A', 'B', 'C', 'D'])) {
                $failed++;
                $errors[] = "Row with question '{$rowData['question']}' has invalid correct_answer (must be A, B, C, or D).";
                continue;
            }
            
            // Validate difficulty
            if (!in_array($rowData['difficulty'], ['easy', 'medium', 'hard'])) {
                $failed++;
                $errors[] = "Row with question '{$rowData['question']}' has invalid difficulty (must be easy, medium, or hard).";
                continue;
            }
            
            // Create the question
            try {
                $rowData['is_active'] = true;
                \App\Models\ChallengeQuestion::create($rowData);
                $success++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Error importing question '{$rowData['question']}': {$e->getMessage()}";
            }
        }
        
        fclose($handle);
        
        $message = "Import completed. {$success} questions imported successfully";
        if ($failed > 0) {
            $message .= ", {$failed} failed.";
            return redirect()->route('admin.challenges.questions.index')
                ->with('warning', $message)
                ->with('import_errors', $errors);
        }
        
        return redirect()->route('admin.challenges.questions.index')
            ->with('success', $message);
    }
}
