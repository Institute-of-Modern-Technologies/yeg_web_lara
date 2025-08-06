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
        // Get the current student by email
        $student = Student::where('email', Auth::user()->email)->first();
        
        if (!$student) {
            // Fallback in case student record isn't found
            return redirect()->route('login')->with('error', 'Student record not found');
        }
        
        // Get current stage (this would ideally come from a student_stage relationship)
        // For now, we'll just get the first stage as an example
        $stage = Stage::first();
        
        // Get activities for the student's current stage
        $stageActivities = [];
        if ($stage) {
            $stageActivities = Activity::whereHas('stages', function($query) use ($stage) {
                $query->where('stages.id', $stage->id);
            })->get();
        }
        
        // Calculate completion percentage
        $completionPercentage = 0;
        $totalActivities = $stageActivities->count();
        
        if ($totalActivities > 0 && $student) {
            // Count completed activities
            $completedActivitiesCount = StudentActivity::where('student_id', $student->id)
                ->whereIn('activity_id', $stageActivities->pluck('id'))
                ->whereNotNull('completed_at')
                ->count();
            
            // Calculate percentage
            $completionPercentage = ($completedActivitiesCount / $totalActivities) * 100;
            $completionPercentage = round($completionPercentage); // Round to nearest integer
        }
        
        return view('student.dashboard', compact('student', 'stage', 'stageActivities', 'completionPercentage'));
    }
}
