<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Activity;
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
            
            return response()->json([
                'success' => true,
                'message' => 'Activity marked as complete',
                'completed_at' => $studentActivity->completed_at->format('Y-m-d H:i:s')
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
            
            return response()->json([
                'success' => true,
                'message' => 'Activity completion status reverted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to revert activity completion: ' . $e->getMessage()
            ], 500);
        }
    }
}
