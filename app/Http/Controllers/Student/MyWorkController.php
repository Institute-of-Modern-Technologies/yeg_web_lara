<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MyWorkController extends Controller
{
    /**
     * Display a listing of the student's works.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get current student by email
        $student = Student::where('email', Auth::user()->email)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student record not found');
        }
        
        // Get student works grouped by type
        $works = [
            'image' => [],
            'video' => [],
            'website' => [],
            'book' => []
        ];
        
        $studentWorks = StudentWork::where('student_id', $student->id)->orderBy('created_at', 'desc')->get();
        
        foreach($studentWorks as $work) {
            $works[$work->type][] = $work;
        }
        
        return view('student.mywork.index', compact('student', 'works'));
    }
    
    /**
     * Show the form for creating a new work.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get current student by email
        $student = Student::where('email', Auth::user()->email)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student record not found');
        }
        
        return view('student.mywork.create', compact('student'));
    }
    
    /**
     * Store a newly created work.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Get current student by email
        $student = Student::where('email', Auth::user()->email)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student record not found');
        }
        
        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:image,video,website,book',
            'website_url' => 'required_if:type,website|nullable|url|max:255',
            'file' => 'required_unless:type,website|nullable|file', // No size limit
        ]);
        
        // Create new work
        $work = new StudentWork();
        $work->student_id = $student->id;
        $work->title = $validated['title'];
        $work->description = $validated['description'];
        $work->type = $validated['type'];
        
        // Handle website type
        if ($work->type === 'website') {
            $work->website_url = $validated['website_url'];
            
            // Generate thumbnail for website using a service or placeholder
            // For now, we'll use a placeholder
            $work->thumbnail = null;
        } else {
            // Handle file uploads (image, video, book)
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = Str::slug($work->title) . '-' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('student-works/' . $work->type . 's', $filename, 'public');
                $work->file_path = $path;
                
                // Generate thumbnail for video using FFmpeg (in a production app)
                // For now, we'll use the file itself as thumbnail for image
                // and a placeholder for video and book
                if ($work->type === 'image') {
                    $work->thumbnail = $path;
                }
            }
        }
        
        $work->save();
        
        return redirect()->route('student.mywork')
            ->with('success', 'Your work has been uploaded successfully and is pending approval.');
    }
    
    /**
     * Display the specified work.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Get current student by email
        $student = Student::where('email', Auth::user()->email)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student record not found');
        }
        
        // Get the work
        $work = StudentWork::findOrFail($id);
        
        // Check if the work belongs to the logged-in student
        if ($work->student_id !== $student->id) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('student.mywork.show', compact('work', 'student'));
    }
    
    /**
     * Delete the specified work item
     */
    public function destroy($id)
    {
        // Get current student by email
        $student = Student::where('email', Auth::user()->email)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student record not found');
        }
        
        $work = StudentWork::findOrFail($id);
        
        // Check if the work belongs to the logged-in student
        if ($work->student_id !== $student->id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Delete the file if it exists
        if ($work->file_path && Storage::disk('public')->exists($work->file_path)) {
            Storage::disk('public')->delete($work->file_path);
        }
        
        $work->delete();
        
        return redirect()->route('student.mywork')
            ->with('success', 'Work has been deleted successfully.');
    }
}
