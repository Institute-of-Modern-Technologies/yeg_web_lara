<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;

// Public routes (no auth required)
Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index']);
Route::get('/about', [\App\Http\Controllers\PageController::class, 'about'])->name('about');
Route::get('/career-guidance', [\App\Http\Controllers\PageController::class, 'careerGuidance'])->name('career.guidance');
Route::get('/programs', [\App\Http\Controllers\PageController::class, 'programs'])->name('programs');
Route::get('/enrollment', [\App\Http\Controllers\PageController::class, 'enrollment'])->name('enrollment');
Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact/send', [\App\Http\Controllers\ContactController::class, 'sendMessage'])->name('contact.send');

// Custom file serving route - no authentication required
Route::get('/files/student-works/{type}/{filename}', [\App\Http\Controllers\FileController::class, 'serveFile'])->name('serve.file');

// Make event details publicly accessible with a clearly named public route
Route::get('/event-details/{id}', [EventController::class, 'publicShow'])->name('events.public.show');

// Original event route (might have auth middleware applied somewhere)
Route::get('/events/{id}', [EventController::class, 'show'])->where('id', '[0-9]+')->name('events.show');

// Event gallery route - displays all events in a modern gallery format
Route::get('/events/gallery', [EventController::class, 'gallery'])->name('events.gallery');

// School Registration Routes
Route::get('/schools/register', [\App\Http\Controllers\SchoolRegistrationController::class, 'showRegistrationForm'])->name('school.register');
Route::post('/schools/register', [\App\Http\Controllers\SchoolRegistrationController::class, 'register'])->name('school.register.submit');
Route::get('/schools/register/success', [\App\Http\Controllers\SchoolRegistrationController::class, 'showSuccess'])->name('school.register.success');

// Teacher Registration Routes
Route::get('/teachers/register', [\App\Http\Controllers\TeacherRegistrationController::class, 'showRegistrationForm'])->name('teacher.register');
Route::post('/teachers/register', [\App\Http\Controllers\TeacherRegistrationController::class, 'submitRegistration'])->name('teacher.register.submit');
Route::get('/teachers/register/success', [\App\Http\Controllers\TeacherRegistrationController::class, 'showSuccessPage'])->name('teacher.register.success');

// Student Registration Routes
Route::prefix('students')->name('student.registration.')->group(function () {
    // Step 1: Program Type Selection
    Route::get('/register', [\App\Http\Controllers\StudentRegistrationController::class, 'showStep1'])->name('step1');
    Route::post('/register/step1', [\App\Http\Controllers\StudentRegistrationController::class, 'processStep1'])->name('process_step1');
    
    // Add a redirect for any GET requests to step1 URL (fixes the method not allowed error)
    Route::get('/register/step1', function() { return redirect('/students/register'); });
    
    // Step 2: School Selection (different for In School vs Other program types)
    Route::post('/register/step2-inschool', [\App\Http\Controllers\StudentRegistrationController::class, 'processStep2InSchool'])->name('process_step2_inschool');
    Route::post('/register/step2-other', [\App\Http\Controllers\StudentRegistrationController::class, 'processStep2Other'])->name('process_step2_other');
    // Direct URL route for step2-other (alternative approach)
    Route::post('/register/process-step2-other', [\App\Http\Controllers\StudentRegistrationController::class, 'processStep2Other']);
    
    // Add redirects for any GET requests to step2 URLs (fixes the method not allowed error)
    Route::get('/register/step2-inschool', function() { return redirect('/students/register'); });
    Route::get('/register/step2-other', function() { return redirect('/students/register'); });
    Route::get('/register/process-step2-other', function() { return redirect('/students/register'); });
    
    // Student details form
    Route::get('/register/details', [\App\Http\Controllers\StudentRegistrationController::class, 'showDetailsForm'])->name('details');
    Route::post('/register/details', [\App\Http\Controllers\StudentRegistrationController::class, 'processDetailsForm'])->name('process_details');
    // Adding matching routes for the /students/ prefix pattern
    Route::get('/students/register/details', [\App\Http\Controllers\StudentRegistrationController::class, 'showDetailsForm']);
    Route::post('/students/register/details', [\App\Http\Controllers\StudentRegistrationController::class, 'processDetailsForm']);
    
    // Step 3: In School - Who is paying?
    Route::post('/register/step3-inschool', [\App\Http\Controllers\StudentRegistrationController::class, 'processStep3InSchool'])->name('process_step3_inschool');
    
    // Payment processing
    Route::post('/register/payment', [\App\Http\Controllers\StudentRegistrationController::class, 'processPayment'])->name('process_payment');
    
    // Success page - simplify route handling to avoid duplication
    $successHandler = function() {
        // Check if we have student registration in session
        if (session()->has('registration.student') && isset(session('registration.student')->id)) {
            // Get the complete student record from database
            $student = \App\Models\Student::find(session('registration.student')->id);
            
            if ($student) {
                // Store the complete student record in session
                session(['registration.student' => $student]);
            }
        }
        
        return view('student.registration.success');
    };
    
    // Register both routes with the same handler
    Route::get('/register/success', $successHandler)->name('student.registration.success');
    Route::get('/students/register/success', $successHandler)->name('student.registration.success');
});

// Student Routes
Route::middleware(['auth', 'user.type:student'])->prefix('student')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('student.dashboard');
    
    // Student Activity Routes
    Route::post('/activities/{id}/complete', [\App\Http\Controllers\Student\ActivityController::class, 'complete'])->name('student.activities.complete');
    Route::post('/activities/{id}/revert', [\App\Http\Controllers\Student\ActivityController::class, 'revert'])->name('student.activities.revert');
    
    // Student My Work Routes
    Route::get('/mywork', [\App\Http\Controllers\Student\MyWorkController::class, 'index'])->name('student.mywork');
    Route::get('/mywork/create', [\App\Http\Controllers\Student\MyWorkController::class, 'create'])->name('student.mywork.create');
    Route::post('/mywork', [\App\Http\Controllers\Student\MyWorkController::class, 'store'])->name('student.mywork.store');
    Route::get('/mywork/category/{type}', [\App\Http\Controllers\Student\MyWorkController::class, 'category'])->name('student.mywork.category');
    Route::get('/mywork/{id}', [\App\Http\Controllers\Student\MyWorkController::class, 'show'])->name('student.mywork.show');
    Route::delete('/mywork/{id}', [\App\Http\Controllers\Student\MyWorkController::class, 'destroy'])->name('student.mywork.delete');
    Route::get('/mywork/{id}/file', [\App\Http\Controllers\Student\MyWorkController::class, 'getFile'])->name('student.mywork.file');
    
    // Student Challenge Routes
    Route::get('/challenges', [\App\Http\Controllers\Student\ChallengeController::class, 'index'])->name('student.challenges.index');
    Route::get('/challenges/create', [\App\Http\Controllers\Student\ChallengeController::class, 'create'])->name('student.challenges.create');
    Route::post('/challenges', [\App\Http\Controllers\Student\ChallengeController::class, 'store'])->name('student.challenges.store');
    Route::get('/challenges/active', [\App\Http\Controllers\Student\ChallengeController::class, 'active'])->name('student.challenges.active');
    Route::get('/challenges/history', [\App\Http\Controllers\Student\ChallengeController::class, 'history'])->name('student.challenges.history');
    Route::get('/challenges/{challenge}', [\App\Http\Controllers\Student\ChallengeController::class, 'show'])->name('student.challenges.show');
    Route::post('/challenges/{challenge}/answer', [\App\Http\Controllers\Student\ChallengeController::class, 'submitAnswer'])->name('student.challenges.submit-answer');
    Route::get('/challenges/{challenge}/result', [\App\Http\Controllers\Student\ChallengeController::class, 'showResult'])->name('student.challenges.result');
});

// Dashboard shortcut route - redirects to appropriate dashboard based on user type
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    if (!$user) {
        return redirect('/login');
    }
    
    $userType = \App\Models\UserType::find($user->user_type_id);
    
    if ($userType) {
        switch ($userType->slug) {
            case 'super_admin':
                return redirect('/admin/dashboard');
            case 'school_admin':
                return redirect('/school/dashboard');
            case 'student':
                return redirect('/student/dashboard');
            default:
                return redirect('/');
        }
    }
    
    return redirect('/');
})->name('dashboard');


// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'user.type:super_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        // Get student counts by status
        $activeStudents = \App\Models\Student::where('status', 'active')->count();
        $pendingStudents = \App\Models\Student::whereNotIn('status', ['active', 'inactive'])->count();
        
        // Get trainer counts
        $trainersCount = \App\Models\Teacher::count();
        $approvedTrainers = \App\Models\Teacher::where('status', 'approved')->count();
        $pendingTrainers = \App\Models\Teacher::where('status', 'pending')->count();
        
        // Get schools
        $schools = \App\Models\School::withCount('students')->orderBy('name')->get();
        
        // Get teachers
        $teachers = \App\Models\Teacher::orderBy('created_at', 'desc')->get();
        
        // Get all pending registrations for notifications (students, teachers, schools)
        $pendingStudentRegistrations = \App\Models\Student::whereNotIn('status', ['active', 'inactive'])
            ->orderBy('created_at', 'desc')
            ->select('id', 'full_name', 'created_at')
            ->selectRaw("'student' as type")
            ->get();
            
        $pendingTeacherRegistrations = \App\Models\Teacher::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->select('id', 'name as full_name', 'created_at')
            ->selectRaw("'teacher' as type")
            ->get();
            
        $pendingSchoolRegistrations = \App\Models\School::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->select('id', 'name as full_name', 'created_at')
            ->selectRaw("'school' as type")
            ->get();
            
        // Combine all pending registrations and sort by most recent
        $allPendingRegistrations = $pendingStudentRegistrations->concat($pendingTeacherRegistrations)
            ->concat($pendingSchoolRegistrations)
            ->sortByDesc('created_at');
            
        // Take the most recent 10 for notifications
        $pendingRegistrations = $allPendingRegistrations->take(10);
        
        return view('admin.dashboard', compact('activeStudents', 'pendingStudents', 'trainersCount', 'approvedTrainers', 'pendingTrainers', 'schools', 'teachers', 'pendingRegistrations'));
    })->name('admin.dashboard');
    
    // Profile Routes
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('admin.profile');
    Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('admin.profile.update');
    
    // User Management Routes
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Hero Section Management Routes
    Route::resource('hero-sections', '\App\Http\Controllers\HeroSectionController')->names([
        'index' => 'admin.hero-sections.index',
        'create' => 'admin.hero-sections.create',
        'store' => 'admin.hero-sections.store',
        'edit' => 'admin.hero-sections.edit',
        'update' => 'admin.hero-sections.update',
        'destroy' => 'admin.hero-sections.destroy'
    ]);
    Route::post('/hero-sections/update-order', [
        '\App\Http\Controllers\HeroSectionController', 'updateOrder'
    ])->name('admin.hero-sections.update-order');
    Route::patch('/hero-sections/{id}/toggle-active', [
        '\App\Http\Controllers\HeroSectionController', 'toggleActive'
    ])->name('admin.hero-sections.toggle-active');
    
    // Event Management Routes
    Route::resource('events', '\App\Http\Controllers\EventController')->names([
        'index' => 'admin.events.index',
        'create' => 'admin.events.create',
        'store' => 'admin.events.store',
        'edit' => 'admin.events.edit',
        'update' => 'admin.events.update',
        'destroy' => 'admin.events.destroy'
    ]);
    Route::post('/events/update-order', [
        '\App\Http\Controllers\EventController', 'updateOrder'
    ])->name('admin.events.update-order');
    Route::patch('/events/{id}/toggle-active', [
        '\App\Http\Controllers\EventController', 'toggleActive'
    ])->name('admin.events.toggle-active');
    
    // Happening Management Routes
    Route::resource('happenings', '\App\Http\Controllers\HappeningController')->names([
        'index' => 'admin.happenings.index',
        'create' => 'admin.happenings.create',
        'store' => 'admin.happenings.store',
        'edit' => 'admin.happenings.edit',
        'update' => 'admin.happenings.update',
        'destroy' => 'admin.happenings.destroy'
    ]);
    Route::post('/happenings/update-order', [
        '\App\Http\Controllers\HappeningController', 'updateOrder'
    ])->name('admin.happenings.update-order');
    Route::patch('/happenings/{id}/toggle-active', [
        '\App\Http\Controllers\HappeningController', 'toggleActive'
    ])->name('admin.happenings.toggle-active');
    
    // Testimonial Management Routes
    Route::resource('testimonials', '\App\Http\Controllers\TestimonialController')->names([
        'index' => 'admin.testimonials.index',
        'create' => 'admin.testimonials.create',
        'store' => 'admin.testimonials.store',
        'edit' => 'admin.testimonials.edit',
        'update' => 'admin.testimonials.update',
        'destroy' => 'admin.testimonials.destroy'
    ]);
    Route::post('/testimonials/update-order', [
        '\App\Http\Controllers\TestimonialController', 'updateOrder'
    ])->name('admin.testimonials.update-order');
    Route::patch('/testimonials/{id}/toggle-active', [
        '\App\Http\Controllers\TestimonialController', 'toggleActive'
    ])->name('admin.testimonials.toggle-active');
    
    // Partner School Management Routes
    Route::resource('partner-schools', '\App\Http\Controllers\PartnerSchoolController')->names([
        'index' => 'admin.partner-schools.index',
        'create' => 'admin.partner-schools.create',
        'store' => 'admin.partner-schools.store',
        'edit' => 'admin.partner-schools.edit',
        'update' => 'admin.partner-schools.update',
        'destroy' => 'admin.partner-schools.destroy'
    ]);
    Route::post('/partner-schools/update-order', [
        '\App\Http\Controllers\PartnerSchoolController', 'updateOrder'
    ])->name('admin.partner-schools.update-order');
    Route::patch('/partner-schools/{id}/toggle-active', [
        '\App\Http\Controllers\PartnerSchoolController', 'toggleActive'
    ])->name('admin.partner-schools.toggle-active');
    
    // Program Types Management Routes
    Route::resource('program-types', '\App\Http\Controllers\Admin\ProgramTypeController')->names([
        'index' => 'admin.program-types.index',
        'create' => 'admin.program-types.create',
        'store' => 'admin.program-types.store',
        'edit' => 'admin.program-types.edit',
        'update' => 'admin.program-types.update',
        'destroy' => 'admin.program-types.destroy'
    ]);
    
    // Schools Management Routes
    Route::resource('schools', '\App\Http\Controllers\Admin\SchoolController')->names([
        'index' => 'admin.schools.index',
        'create' => 'admin.schools.create',
        'store' => 'admin.schools.store',
        'show' => 'admin.schools.show',
        'edit' => 'admin.schools.edit',
        'update' => 'admin.schools.update',
        'destroy' => 'admin.schools.destroy'
    ]);
    Route::patch('/schools/{id}/status', ['\App\Http\Controllers\Admin\SchoolController', 'updateStatus'])->name('admin.schools.update-status');
    
    // Fee Management Routes
    Route::resource('fees', '\App\Http\Controllers\Admin\FeeController')->names([
        'index' => 'admin.fees.index',
        'create' => 'admin.fees.create',
        'store' => 'admin.fees.store',
        'edit' => 'admin.fees.edit',
        'update' => 'admin.fees.update',
        'destroy' => 'admin.fees.destroy'
    ]);
    Route::patch('/fees/{fee}/toggle-status', ['\App\Http\Controllers\Admin\FeeController', 'toggleStatus'])->name('admin.fees.toggle-status');
    
    // Student Management Routes
    Route::get('/students', ['\App\Http\Controllers\Admin\StudentController', 'index'])->name('admin.students.index');
    Route::get('/students/{student}/payment/create', ['\App\Http\Controllers\Admin\PaymentController', 'create'])->name('admin.payments.create');
    Route::get('/students/create', ['\App\Http\Controllers\Admin\StudentController', 'create'])->name('admin.students.create');
    Route::post('/students', ['\App\Http\Controllers\Admin\StudentController', 'store'])->name('admin.students.store');
    Route::delete('/students/bulk-destroy', ['\App\Http\Controllers\Admin\StudentController', 'bulkDestroy'])->name('admin.students.bulk-destroy');
    Route::get('/students/import', ['\App\Http\Controllers\Admin\StudentController', 'showImportForm'])->name('admin.students.import');
    Route::post('/students/import', ['\App\Http\Controllers\Admin\StudentController', 'validateImport'])->name('admin.students.import.validate');
    Route::post('/students/import/match-schools', ['\App\Http\Controllers\Admin\StudentController', 'matchSchools'])->name('admin.students.import.match_schools');
    Route::post('/students/import/process', ['\App\Http\Controllers\Admin\StudentController', 'processImport'])->name('admin.students.import.process');
    Route::get('/students/export', ['\App\Http\Controllers\Admin\StudentController', 'export'])->name('admin.students.export');
    
    // Bulk student actions - IMPORTANT: These must be defined BEFORE the /students/{student} routes
    Route::put('/students/bulk-promote', ['\App\Http\Controllers\Admin\StudentController', 'bulkPromoteStage'])->name('admin.students.bulk-promote');
    Route::put('/students/bulk-repeat', ['\App\Http\Controllers\Admin\StudentController', 'bulkRepeatStage'])->name('admin.students.bulk-repeat');
    
    // Individual student routes with parameters
    Route::get('/students/{student}', ['\App\Http\Controllers\Admin\StudentController', 'show'])->name('admin.students.show');
    Route::get('/students/{student}/edit', ['\App\Http\Controllers\Admin\StudentController', 'edit'])->name('admin.students.edit');
    Route::put('/students/{student}', ['\App\Http\Controllers\Admin\StudentController', 'update'])->name('admin.students.update');
    Route::delete('/students/{student}', ['\App\Http\Controllers\Admin\StudentController', 'destroy'])->name('admin.students.destroy');
    Route::post('/students/{student}/approve', ['\App\Http\Controllers\Admin\StudentController', 'approveStudent'])->name('admin.students.approve');
    Route::post('/students/{student}/promote-stage', ['\App\Http\Controllers\Admin\StudentController', 'promoteStage'])->name('admin.students.promote-stage');
    Route::get('/students/{student}/get-stage-info', ['\App\Http\Controllers\Admin\StudentController', 'getStageInfo'])->name('admin.students.get-stage-info');
    Route::get('/stages/get-active', ['\App\Http\Controllers\Admin\StageController', 'getActiveStages'])->name('admin.stages.get-active');
    Route::post('/students/{student}/repeat-stage', ['\App\Http\Controllers\Admin\StudentController', 'repeatStage'])->name('admin.students.repeat-stage');
    Route::post('/students/{student}/change-stage', ['\App\Http\Controllers\Admin\StudentController', 'changeStage'])->name('admin.students.change-stage');
    
    // School Logo Management Routes
    Route::resource('school-logos', '\App\Http\Controllers\Admin\SchoolLogoController')->names([
        'index' => 'admin.school-logos.index',
        'create' => 'admin.school-logos.create',
        'store' => 'admin.school-logos.store',
        'edit' => 'admin.school-logos.edit',
        'update' => 'admin.school-logos.update',
        'destroy' => 'admin.school-logos.destroy'
    ]);
    Route::post('/school-logos/update-order', ['\App\Http\Controllers\Admin\SchoolLogoController', 'updateOrder'])->name('admin.school-logos.update-order');
    Route::patch('/school-logos/{id}/toggle-active', ['\App\Http\Controllers\Admin\SchoolLogoController', 'toggleActive'])->name('admin.school-logos.toggle-active');
    
    // Trainer Management Routes
    Route::resource('trainers', '\App\Http\Controllers\Admin\TrainerController')->names([
        'index' => 'admin.trainers.index',
        'create' => 'admin.trainers.create',
        'store' => 'admin.trainers.store',
        'show' => 'admin.trainers.show',
        'edit' => 'admin.trainers.edit',
        'update' => 'admin.trainers.update',
        'destroy' => 'admin.trainers.destroy'
    ]);
    Route::patch('/trainers/{trainer}/status', ['\App\Http\Controllers\Admin\TrainerController', 'updateStatus'])->name('admin.trainers.update-status');
    Route::get('/trainers/create-accounts', ['\App\Http\Controllers\Admin\TrainerController', 'createAccountsForApprovedTrainers'])->name('admin.trainers.create-accounts');
    
    // Activities Setup Management Routes
    Route::resource('activities', '\App\Http\Controllers\Admin\ActivitySetupController')->names([
        'index' => 'admin.activities.index',
        'create' => 'admin.activities.create',
        'store' => 'admin.activities.store',
        'edit' => 'admin.activities.edit',
        'update' => 'admin.activities.update',
        'destroy' => 'admin.activities.destroy'
    ]);
    

    
    // Stages Setup Management Routes
    Route::resource('stages', '\App\Http\Controllers\Admin\StageController')->names([
        'index' => 'admin.stages.index',
        'store' => 'admin.stages.store',
        'edit' => 'admin.stages.edit',
        'update' => 'admin.stages.update',
        'destroy' => 'admin.stages.destroy'
    ]);
    Route::post('/stages/update-order', '\App\Http\Controllers\Admin\StageController@updateOrder')->name('admin.stages.update-order');
    Route::patch('/stages/{stage}/toggle-active', '\App\Http\Controllers\Admin\StageController@toggleActive')->name('admin.stages.toggle-active');
    Route::post('/stages/{stage}/toggle-status', '\App\Http\Controllers\Admin\StageController@toggleStatus')->name('admin.stages.toggle-status');
    
    // Challenge Categories Management Routes
    Route::resource('challenge-categories', '\App\Http\Controllers\Admin\ChallengeCategoryController')->names([
        'index' => 'admin.challenge-categories.index',
        'create' => 'admin.challenge-categories.create',
        'store' => 'admin.challenge-categories.store',
        'edit' => 'admin.challenge-categories.edit',
        'update' => 'admin.challenge-categories.update',
        'destroy' => 'admin.challenge-categories.destroy'
    ]);
    Route::patch('/challenge-categories/{category}/toggle-active', '\App\Http\Controllers\Admin\ChallengeCategoryController@toggleActive')
        ->name('admin.challenge-categories.toggle-active');
    
    // Challenge Questions Management Routes
    Route::resource('challenge-questions', '\App\Http\Controllers\Admin\ChallengeQuestionController')->names([
        'index' => 'admin.challenge-questions.index',
        'create' => 'admin.challenge-questions.create',
        'store' => 'admin.challenge-questions.store',
        'edit' => 'admin.challenge-questions.edit',
        'update' => 'admin.challenge-questions.update',
        'destroy' => 'admin.challenge-questions.destroy'
    ]);
    Route::get('/challenge-questions/category/{category}', '\App\Http\Controllers\Admin\ChallengeQuestionController@indexByCategory')
        ->name('admin.challenge-questions.by-category');
    Route::patch('/challenge-questions/{question}/toggle-active', '\App\Http\Controllers\Admin\ChallengeQuestionController@toggleActive')
        ->name('admin.challenge-questions.toggle-active');
    
    // Payment Management Routes
    Route::post('/payments/store', '\App\Http\Controllers\Admin\PaymentController@store')->name('admin.payments.store');
    Route::get('/payments/student/{student}', '\App\Http\Controllers\Admin\PaymentController@getStudentPayments')->name('admin.payments.student');
    Route::get('/payments/receipt/{id}', '\App\Http\Controllers\Admin\PaymentController@showReceipt')->name('admin.payments.receipt');
    
    // Billing Management Routes
    Route::get('/billing', '\App\Http\Controllers\Admin\BillingController@index')->name('admin.billing.index');
    Route::get('/billing/{student}', '\App\Http\Controllers\Admin\BillingController@show')->name('admin.billing.show');
});

// School Admin Routes
Route::middleware(['auth', 'user.type:school_admin'])->prefix('school')->group(function () {
    Route::get('/dashboard', function () {
        return view('school.dashboard');
    })->name('school.dashboard');
});

// Student Routes
Route::middleware(['auth', 'user.type:student'])->prefix('student')->group(function () {
    // Student Dashboard Route
    Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('student.dashboard');
    
    // Student Dashboard Progress API Route
    Route::get('/dashboard/progress', [\App\Http\Controllers\Student\DashboardController::class, 'getProgress'])->name('student.dashboard.progress');
    
    // Legacy Dashboard Route (keeping for compatibility)
    Route::get('/dashboard-legacy', function () {
        // Get the authenticated student user
        $user = auth()->user();
        
        // Get the student record associated with this user
        $student = \App\Models\Student::where('email', $user->email)->first();
        
        // Check if student has a stage assigned
        $stage = null;
        $stageActivities = collect();
        
        if ($student && $student->stage_id) {
            $stage = \App\Models\Stage::find($student->stage_id);
            
            // Get activities for this stage
            if ($stage) {
                $stageActivities = $stage->activities()->get();
                
                // Check completion status for each activity
                foreach ($stageActivities as $activity) {
                    $completedActivity = \App\Models\StudentActivity::where('student_id', $student->id)
                        ->where('activity_id', $activity->id)
                        ->whereNotNull('completed_at')
                        ->first();
                    
                    // Add completed property to the activity object
                    $activity->completed = $completedActivity ? true : false;
                    
                    // Add completed_at date if available
                    $activity->completed_at = $completedActivity ? $completedActivity->completed_at : null;
                }
            }
        }
        
        // Get all stages to show progression path
        $allStages = \App\Models\Stage::where('status', 'active')
                           ->orderBy('order')
                           ->get();
                           
        // Eager load activities for all stages
        $allStages->load('activities');
        
        return view('student.dashboard', compact('student', 'stage', 'stageActivities', 'allStages'));
    })->name('student.dashboard');
    
    // Activity Routes
    Route::post('/activities/{id}/complete', [\App\Http\Controllers\Student\ActivityController::class, 'complete'])->name('student.activities.complete');
    Route::post('/activities/{id}/revert', [\App\Http\Controllers\Student\ActivityController::class, 'revert'])->name('student.activities.revert');
    
    // AJAX endpoint to get stage activities
    Route::get('/stage/{stage}/activities', function (\App\Models\Stage $stage) {
        $activities = $stage->activities()->get();
        return response()->json([
            'success' => true,
            'activities' => $activities
        ]);
    })->name('student.stage.activities');
});

// TEMPORARY DEBUG ROUTE - Remove after fixing challenges issue
Route::middleware(['auth', 'user.type:student'])->get('/debug-student', function () {
    $user = auth()->user();
    $student = $user ? $user->student : null;
    
    $debug = [
        'authenticated' => auth()->check(),
        'user_id' => $user ? $user->id : null,
        'username' => $user ? $user->username : null,
        'user_type_id' => $user ? $user->user_type_id : null,
        'student_exists' => $student ? true : false,
        'student_id' => $student ? $student->id : null,
        'student_name' => $student ? $student->full_name : null,
        'student_user_id' => $student ? $student->user_id : null,
    ];
    
    return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
})->name('debug.student');
