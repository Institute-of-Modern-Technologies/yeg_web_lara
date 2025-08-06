<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Stage;
use App\Models\Student;
use App\Models\StudentActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Mark an activity as complete.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete(Request $request, $id)
    {
        try {
            $activity = Activity::findOrFail($id);
            // Get student by matching email with the authenticated user
            $student = Student::where('email', Auth::user()->email)->firstOrFail();
            
            // Check if record already exists
            $studentActivity = StudentActivity::where('student_id', $student->id)
                ->where('activity_id', $activity->id)
                ->first();
            
            if (!$studentActivity) {
                $studentActivity = new StudentActivity();
                $studentActivity->student_id = $student->id;
                $studentActivity->activity_id = $activity->id;
            }
            
            $studentActivity->completed_at = now();
            $studentActivity->save();
            
            // Calculate completion percentage
            $completionPercentage = $this->calculateCompletionPercentage($student);
            
            return response()->json([
                'success' => true,
                'message' => 'Activity marked as complete',
                'completed_at' => $studentActivity->completed_at->format('Y-m-d H:i:s'),
                'completionPercentage' => $completionPercentage
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark activity as complete: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Revert activity completion status.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function revert(Request $request, $id)
    {
        try {
            $activity = Activity::findOrFail($id);
            // Get student by matching email with the authenticated user
            $student = Student::where('email', Auth::user()->email)->firstOrFail();
            
            $studentActivity = StudentActivity::where('student_id', $student->id)
                ->where('activity_id', $activity->id)
                ->first();
            
            if ($studentActivity) {
                $studentActivity->completed_at = null;
                $studentActivity->save();
            }
            
            // Calculate completion percentage
            $completionPercentage = $this->calculateCompletionPercentage($student);
            
            return response()->json([
                'success' => true,
                'message' => 'Activity completion status reverted',
                'completionPercentage' => $completionPercentage
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to revert activity completion: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Calculate completion percentage for a student
     * 
     * @param Student $student
     * @return int
     */
    protected function calculateCompletionPercentage(Student $student)
    {
        try {
            // Get current stage (ideally should come from a student_stage relationship)
            // For now, we'll just get the first stage as an example
            $stage = Stage::first();
            
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
}
