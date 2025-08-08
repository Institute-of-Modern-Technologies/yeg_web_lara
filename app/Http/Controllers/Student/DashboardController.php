<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Stage;
use App\Models\Student;
use App\Models\StudentActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the student dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the current student using the user-student relationship
        $student = Auth::user()->student;
        
        if (!$student) {
            // Fallback: try to find by email as secondary method
            $student = Student::where('email', Auth::user()->email)->first();
            
            if (!$student) {
                return redirect()->route('login')->with('error', 'Student record not found. Please contact support.');
            }
        }
        
        // Get current stage - use a more robust method
        $stage = $this->getCurrentStageForStudent($student);
        
        // Get activities for the student's current stage
        $stageActivities = collect();
        if ($stage) {
            $stageActivities = Activity::whereHas('stages', function($query) use ($stage) {
                $query->where('stages.id', $stage->id);
            })->get();
        }
        
        // Calculate completion percentage using the same method as ActivityController
        $completionPercentage = $this->calculateCompletionPercentage($student, $stage);
        
        // Ensure completionPercentage is never null
        $completionPercentage = $completionPercentage ?? 0;
        
        return view('student.dashboard', compact('student', 'stage', 'stageActivities', 'completionPercentage'));
    }
    
    /**
     * Get the current stage for a student
     * This method provides a centralized way to determine a student's current stage
     *
     * @param Student $student
     * @return Stage|null
     */
    private function getCurrentStageForStudent(Student $student)
    {
        // TODO: In the future, this should check a student_stages table or similar
        // For now, we'll use the first stage but make it consistent across the app
        return Stage::first();
    }
    
    /**
     * Calculate completion percentage for a student
     * This method should match the one in ActivityController for consistency
     * 
     * @param Student $student
     * @param Stage|null $stage
     * @return int
     */
    private function calculateCompletionPercentage(Student $student, $stage = null)
    {
        try {
            if (!$stage) {
                $stage = $this->getCurrentStageForStudent($student);
            }
            
            if (!$stage) {
                return 0;
            }
            
            // Get activities for the student's current stage
            $stageActivities = Activity::whereHas('stages', function($query) use ($stage) {
                $query->where('stages.id', $stage->id);
            })->get();
            
            $totalActivities = $stageActivities->count();
            
            if ($totalActivities === 0) {
                return 0;
            }
            
            // Count completed activities
            $completedActivitiesCount = StudentActivity::where('student_id', $student->id)
                ->whereIn('activity_id', $stageActivities->pluck('id'))
                ->whereNotNull('completed_at')
                ->count();
            
            // Calculate percentage
            $percentage = ($completedActivitiesCount / $totalActivities) * 100;
            return round($percentage); // Round to nearest integer
        } catch (\Exception $e) {
            // If any error occurs, return 0%
            return 0;
        }
    }
    
    /**
     * Get progress data for AJAX requests
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProgress()
    {
        try {
            // Get the current student using the user-student relationship
            $student = Auth::user()->student;
            
            if (!$student) {
                // Fallback: try to find by email as secondary method
                $student = Student::where('email', Auth::user()->email)->first();
                
                if (!$student) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Student record not found'
                    ], 404);
                }
            }
            
            // Get current stage
            $stage = $this->getCurrentStageForStudent($student);
            
            // Calculate completion percentage
            $completionPercentage = $this->calculateCompletionPercentage($student, $stage);
            
            return response()->json([
                'success' => true,
                'completionPercentage' => $completionPercentage ?? 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate progress',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
