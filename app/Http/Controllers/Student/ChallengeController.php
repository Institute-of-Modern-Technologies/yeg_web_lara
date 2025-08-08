<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    /**
     * Display a listing of challenges for the student.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $student = auth()->user()->student;
        
        // Check if student record exists
        if (!$student) {
            return redirect('/')->with('error', 'No student profile found. Please contact support.');
        }
        
        $pendingChallenges = \App\Models\Challenge::with(['challenger', 'category'])
            ->where('opponent_id', $student->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $activeChallenges = \App\Models\Challenge::with(['challenger', 'opponent', 'category'])
            ->where(function($query) use ($student) {
                $query->where('challenger_id', $student->id)
                      ->orWhere('opponent_id', $student->id);
            })
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $completedChallenges = \App\Models\Challenge::with(['challenger', 'opponent', 'category', 'result'])
            ->where(function($query) use ($student) {
                $query->where('challenger_id', $student->id)
                      ->orWhere('opponent_id', $student->id);
            })
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get challenge statistics
        $stats = $student->challengeStats ?? new \App\Models\StudentChallengeStat();
        
        // Get available categories
        $categories = \App\Models\ChallengeCategory::where('is_active', true)
            ->orderBy('display_order')
            ->get();
            
        // Get top students for leaderboard
        $leaderboard = \App\Models\StudentChallengeStat::with('student')
            ->orderBy('xp_points', 'desc')
            ->take(10)
            ->get();
            
        return view('student.challenges.index', compact(
            'pendingChallenges', 
            'activeChallenges', 
            'completedChallenges', 
            'stats', 
            'categories', 
            'leaderboard'
        ));
    }
    
    /**
     * Show form to create a new challenge.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $student = auth()->user()->student;
        
        // Get available categories
        $categories = \App\Models\ChallengeCategory::where('is_active', true)
            ->orderBy('display_order')
            ->get();
            
        // Get available opponents (excluding the current student)
        $opponents = \App\Models\Student::where('id', '!=', $student->id)
            ->orderBy('full_name')
            ->get();
            
        return view('student.challenges.create', compact('categories', 'opponents'));
    }
    
    /**
     * Store a newly created challenge.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $student = auth()->user()->student;
        
        $validatedData = $request->validate([
            'category_id' => 'required|exists:challenge_categories,id',
            'opponent_type' => 'required|in:specific,random',
            'opponent_id' => 'required_if:opponent_type,specific|exists:students,id',
        ]);
        
        // Create challenge data
        $challengeData = [
            'challenger_id' => $student->id,
            'category_id' => $validatedData['category_id'],
            'status' => 'pending',
            'is_random_opponent' => $validatedData['opponent_type'] === 'random',
            'expires_at' => now()->addDays(3), // Challenge expires in 3 days if not accepted
        ];
        
        if ($validatedData['opponent_type'] === 'specific') {
            // Check if opponent_id is not the current student
            if ($validatedData['opponent_id'] == $student->id) {
                return redirect()->back()->with('error', 'You cannot challenge yourself!')->withInput();
            }
            
            $challengeData['opponent_id'] = $validatedData['opponent_id'];
        } else {
            // For random opponent, system will select one
            $randomOpponent = \App\Models\Student::inRandomOrder()
                ->where('id', '!=', $student->id)
                ->first();
                
            if (!$randomOpponent) {
                return redirect()->back()->with('error', 'No opponents available for random challenge.')->withInput();
            }
            
            $challengeData['opponent_id'] = $randomOpponent->id;
        }
        
        // Create the challenge
        $challenge = \App\Models\Challenge::create($challengeData);
        
        // Select random questions for the challenge based on the category
        $questions = \App\Models\ChallengeQuestion::where('category_id', $validatedData['category_id'])
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(5) // 5 questions per challenge
            ->get();
            
        // Attach questions to the challenge with question_order
        foreach ($questions as $index => $question) {
            $challenge->questions()->attach($question->id, ['question_order' => $index + 1]);
        }
        
        // Update student stats
        $stats = $student->challengeStats ?? new \App\Models\StudentChallengeStat(['student_id' => $student->id]);
        $stats->challenges_initiated = ($stats->challenges_initiated ?? 0) + 1;
        $stats->save();
        
        // TODO: Send notification to opponent
        
        return redirect()->route('student.challenges.index')
            ->with('success', 'Challenge created successfully! Waiting for your opponent to accept.');
    }
    
    /**
     * Accept a challenge invitation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function accept($id)
    {
        $student = auth()->user()->student;
        $challenge = \App\Models\Challenge::findOrFail($id);
        
        // Check if the student is the opponent of this challenge
        if ($challenge->opponent_id !== $student->id) {
            return redirect()->route('student.challenges.index')
                ->with('error', 'You are not authorized to accept this challenge.');
        }
        
        // Check if the challenge is still pending
        if ($challenge->status !== 'pending') {
            return redirect()->route('student.challenges.index')
                ->with('error', 'This challenge cannot be accepted anymore.');
        }
        
        // Update challenge status
        $challenge->status = 'active';
        $challenge->save();
        
        // Update student stats
        $stats = $student->challengeStats ?? new \App\Models\StudentChallengeStat(['student_id' => $student->id]);
        $stats->challenges_received = ($stats->challenges_received ?? 0) + 1;
        $stats->save();
        
        // TODO: Send notification to challenger
        
        return redirect()->route('student.challenges.show', $challenge->id)
            ->with('success', 'Challenge accepted! You can now start playing.');
    }
    
    /**
     * Reject a challenge invitation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject($id)
    {
        $student = auth()->user()->student;
        $challenge = \App\Models\Challenge::findOrFail($id);
        
        // Check if the student is the opponent of this challenge
        if ($challenge->opponent_id !== $student->id) {
            return redirect()->route('student.challenges.index')
                ->with('error', 'You are not authorized to reject this challenge.');
        }
        
        // Check if the challenge is still pending
        if ($challenge->status !== 'pending') {
            return redirect()->route('student.challenges.index')
                ->with('error', 'This challenge cannot be rejected anymore.');
        }
        
        // Update challenge status
        $challenge->status = 'rejected';
        $challenge->save();
        
        // TODO: Send notification to challenger
        
        return redirect()->route('student.challenges.index')
            ->with('success', 'Challenge rejected.');
    }
    
    /**
     * Display the specified challenge.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = auth()->user()->student;
        $challenge = \App\Models\Challenge::with(['challenger', 'opponent', 'category', 'questions', 'responses'])
            ->findOrFail($id);
            
        // Check if the student is a participant in this challenge
        if ($challenge->challenger_id !== $student->id && $challenge->opponent_id !== $student->id) {
            return redirect()->route('student.challenges.index')
                ->with('error', 'You are not authorized to view this challenge.');
        }
        
        // Get the student's responses to this challenge
        $responses = \App\Models\ChallengeResponse::where('challenge_id', $challenge->id)
            ->where('student_id', $student->id)
            ->get()
            ->keyBy('question_id');
            
        // Check if the challenge is completed
        $isCompleted = $challenge->status === 'completed';
        $result = $isCompleted ? $challenge->result : null;
        
        // Get questions ordered by question_order
        $questions = $challenge->questions()->orderBy('challenge_question.question_order')->get();
        
        // Determine current question (first unanswered question)
        $currentQuestion = null;
        $allAnswered = true;
        
        foreach ($questions as $question) {
            if (!isset($responses[$question->id])) {
                $currentQuestion = $question;
                $allAnswered = false;
                break;
            }
        }
        
        // If all questions answered but challenge not completed yet
        if ($allAnswered && $challenge->status === 'active') {
            // Check if opponent has completed all questions too
            $opponentCompleted = true;
            $opponentId = ($challenge->challenger_id === $student->id) ? $challenge->opponent_id : $challenge->challenger_id;
            
            foreach ($questions as $question) {
                $opponentResponse = \App\Models\ChallengeResponse::where('challenge_id', $challenge->id)
                    ->where('student_id', $opponentId)
                    ->where('question_id', $question->id)
                    ->first();
                    
                if (!$opponentResponse) {
                    $opponentCompleted = false;
                    break;
                }
            }
            
            // If both students completed all questions, calculate results
            if ($opponentCompleted) {
                $this->calculateResults($challenge);
                
                // Refresh the challenge with results
                $challenge->refresh();
                $isCompleted = true;
                $result = $challenge->result;
            }
        }
        
        return view('student.challenges.show', compact(
            'challenge',
            'questions',
            'responses',
            'currentQuestion',
            'isCompleted',
            'result',
            'student'
        ));
    }
    
    /**
     * Submit an answer for a challenge question.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function submitAnswer(Request $request, $id)
    {
        $student = auth()->user()->student;
        $challenge = \App\Models\Challenge::findOrFail($id);
        
        // Check if the student is a participant in this challenge
        if ($challenge->challenger_id !== $student->id && $challenge->opponent_id !== $student->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to participate in this challenge.'
            ], 403);
        }
        
        // Check if the challenge is active
        if ($challenge->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'This challenge is not active.'
            ], 400);
        }
        
        // Validate request data
        $validatedData = $request->validate([
            'question_id' => 'required|exists:challenge_questions,id',
            'answer' => 'required|in:A,B,C,D',
            'time_taken' => 'required|integer|min:1',
        ]);
        
        $question = \App\Models\ChallengeQuestion::findOrFail($validatedData['question_id']);
        
        // Check if question belongs to this challenge
        $belongsToChallenge = $challenge->questions()->where('challenge_question_id', $question->id)->exists();
        if (!$belongsToChallenge) {
            return response()->json([
                'success' => false,
                'message' => 'This question does not belong to the current challenge.'
            ], 400);
        }
        
        // Check if the question has already been answered by this student
        $existingResponse = \App\Models\ChallengeResponse::where('challenge_id', $challenge->id)
            ->where('student_id', $student->id)
            ->where('question_id', $question->id)
            ->first();
            
        if ($existingResponse) {
            return response()->json([
                'success' => false,
                'message' => 'You have already answered this question.'
            ], 400);
        }
        
        // Check if the answer is correct
        $isCorrect = $validatedData['answer'] === $question->correct_answer;
        
        // Create the response
        $response = \App\Models\ChallengeResponse::create([
            'challenge_id' => $challenge->id,
            'student_id' => $student->id,
            'question_id' => $question->id,
            'selected_answer' => $validatedData['answer'],
            'is_correct' => $isCorrect,
            'time_taken' => $validatedData['time_taken'],
        ]);
        
        // Update student stats
        $stats = $student->challengeStats ?? new \App\Models\StudentChallengeStat(['student_id' => $student->id]);
        $stats->questions_answered = ($stats->questions_answered ?? 0) + 1;
        
        if ($isCorrect) {
            $stats->correct_answers = ($stats->correct_answers ?? 0) + 1;
        }
        
        $stats->total_time_spent = ($stats->total_time_spent ?? 0) + $validatedData['time_taken'];
        $stats->save();
        
        // Check if this was the last question for this student
        $answeredCount = \App\Models\ChallengeResponse::where('challenge_id', $challenge->id)
            ->where('student_id', $student->id)
            ->count();
            
        $totalQuestions = $challenge->questions()->count();
        
        if ($answeredCount >= $totalQuestions) {
            // Check if opponent has also completed all questions
            $opponentId = ($challenge->challenger_id === $student->id) ? $challenge->opponent_id : $challenge->challenger_id;
            $opponentAnsweredCount = \App\Models\ChallengeResponse::where('challenge_id', $challenge->id)
                ->where('student_id', $opponentId)
                ->count();
                
            if ($opponentAnsweredCount >= $totalQuestions) {
                // Both students have completed all questions, calculate results
                $this->calculateResults($challenge);
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Answer submitted successfully',
            'is_correct' => $isCorrect,
            'correct_answer' => $question->correct_answer,
            'explanation' => $question->explanation
        ]);
    }
    
    /**
     * Display the leaderboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function leaderboard()
    {
        $student = auth()->user()->student;
        
        // Get top students for leaderboard
        $topStudents = \App\Models\StudentChallengeStat::with('student')
            ->orderBy('xp_points', 'desc')
            ->take(20)
            ->get();
            
        // Get current student's rank
        $stats = $student->challengeStats;
        $rank = $stats ? $stats->getRankAttribute() : null;
        
        return view('student.challenges.leaderboard', compact('topStudents', 'student', 'rank', 'stats'));
    }
    
    /**
     * Calculate and store results for a completed challenge.
     *
     * @param  \App\Models\Challenge  $challenge
     * @return void
     */
    protected function calculateResults($challenge)
    {
        // Check if results are already calculated
        if ($challenge->status === 'completed') {
            return;
        }
        
        $challenger = $challenge->challenger;
        $opponent = $challenge->opponent;
        
        // Get all questions in this challenge
        $questions = $challenge->questions;
        $totalPoints = $questions->sum('points');
        
        // Calculate challenger stats
        $challengerResponses = \App\Models\ChallengeResponse::where('challenge_id', $challenge->id)
            ->where('student_id', $challenger->id)
            ->get();
            
        $challengerCorrect = $challengerResponses->where('is_correct', true)->count();
        $challengerScore = 0;
        $challengerTime = 0;
        
        foreach ($challengerResponses as $response) {
            if ($response->is_correct) {
                // Calculate score based on time taken (faster = more points)
                $question = $questions->firstWhere('id', $response->question_id);
                $timeRatio = min(1, $response->time_taken / $question->time_allowed);
                $challengerScore += $question->points * (1 - $timeRatio * 0.5); // Time factor reduces score by up to 50%
            }
            $challengerTime += $response->time_taken;
        }
        
        // Calculate opponent stats
        $opponentResponses = \App\Models\ChallengeResponse::where('challenge_id', $challenge->id)
            ->where('student_id', $opponent->id)
            ->get();
            
        $opponentCorrect = $opponentResponses->where('is_correct', true)->count();
        $opponentScore = 0;
        $opponentTime = 0;
        
        foreach ($opponentResponses as $response) {
            if ($response->is_correct) {
                // Calculate score based on time taken (faster = more points)
                $question = $questions->firstWhere('id', $response->question_id);
                $timeRatio = min(1, $response->time_taken / $question->time_allowed);
                $opponentScore += $question->points * (1 - $timeRatio * 0.5); // Time factor reduces score by up to 50%
            }
            $opponentTime += $response->time_taken;
        }
        
        // Determine the winner
        $winnerId = null;
        $result = 'draw';
        
        if ($challengerScore > $opponentScore) {
            $winnerId = $challenger->id;
            $result = 'challenger_won';
        } elseif ($opponentScore > $challengerScore) {
            $winnerId = $opponent->id;
            $result = 'opponent_won';
        } else {
            // If scores are tied, the faster student wins
            if ($challengerTime < $opponentTime) {
                $winnerId = $challenger->id;
                $result = 'challenger_won';
            } elseif ($opponentTime < $challengerTime) {
                $winnerId = $opponent->id;
                $result = 'opponent_won';
            }
            // If times are also equal, it's a draw (result already set to 'draw')
        }
        
        // Create the result record
        \App\Models\ChallengeResult::create([
            'challenge_id' => $challenge->id,
            'winner_id' => $winnerId,
            'challenger_score' => round($challengerScore, 1),
            'opponent_score' => round($opponentScore, 1),
            'challenger_time' => $challengerTime,
            'opponent_time' => $opponentTime,
            'result' => $result,
        ]);
        
        // Update challenge status
        $challenge->status = 'completed';
        $challenge->completed_at = now();
        $challenge->save();
        
        // Update challenger stats
        $challengerStats = $challenger->challengeStats ?? new \App\Models\StudentChallengeStat(['student_id' => $challenger->id]);
        
        if ($result === 'challenger_won') {
            $challengerStats->challenges_won = ($challengerStats->challenges_won ?? 0) + 1;
            $challengerStats->xp_points = ($challengerStats->xp_points ?? 0) + ceil($challengerScore * 2); // Double XP for winning
        } elseif ($result === 'opponent_won') {
            $challengerStats->challenges_lost = ($challengerStats->challenges_lost ?? 0) + 1;
            $challengerStats->xp_points = ($challengerStats->xp_points ?? 0) + ceil($challengerScore); // Regular XP for losing
        } else {
            $challengerStats->challenges_drawn = ($challengerStats->challenges_drawn ?? 0) + 1;
            $challengerStats->xp_points = ($challengerStats->xp_points ?? 0) + ceil($challengerScore * 1.5); // 1.5x XP for drawing
        }
        
        $challengerStats->save();
        
        // Update opponent stats
        $opponentStats = $opponent->challengeStats ?? new \App\Models\StudentChallengeStat(['student_id' => $opponent->id]);
        
        if ($result === 'opponent_won') {
            $opponentStats->challenges_won = ($opponentStats->challenges_won ?? 0) + 1;
            $opponentStats->xp_points = ($opponentStats->xp_points ?? 0) + ceil($opponentScore * 2); // Double XP for winning
        } elseif ($result === 'challenger_won') {
            $opponentStats->challenges_lost = ($opponentStats->challenges_lost ?? 0) + 1;
            $opponentStats->xp_points = ($opponentStats->xp_points ?? 0) + ceil($opponentScore); // Regular XP for losing
        } else {
            $opponentStats->challenges_drawn = ($opponentStats->challenges_drawn ?? 0) + 1;
            $opponentStats->xp_points = ($opponentStats->xp_points ?? 0) + ceil($opponentScore * 1.5); // 1.5x XP for drawing
        }
        
        $opponentStats->save();
        
        // TODO: Send notifications to both students
    }
}
