@extends('layouts.student-unified')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{ route('student.challenges.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left"></i> Back to Challenges
                    </a>
                </div>
                <h4 class="page-title">Active Challenge</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Challenge Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-1">
                                <span class="badge" style="background-color: {{ $challenge->category->color }}">
                                    <i class="{{ $challenge->category->icon }}"></i> 
                                    {{ $challenge->category->name }} Challenge
                                </span>
                            </h4>
                            <p class="text-muted mb-0">
                                {{ $challenge->challenger->full_name }} vs 
                                {{ $challenge->opponent->full_name ?? 'Random Opponent' }}
                            </p>
                        </div>
                        <div class="text-center">
                            <h5 class="mb-0">Question <span id="current-question">{{ $currentQuestionIndex + 1 }}</span> of {{ $totalQuestions }}</h5>
                            <div class="progress mt-2" style="height: 6px; width: 200px;">
                                <div class="progress-bar bg-primary" role="progressbar" 
                                     style="width: {{ ($currentQuestionIndex + 1) / $totalQuestions * 100 }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- If the challenge is pending acceptance -->
                    @if($challenge->status == 'pending')
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="mdi mdi-sword-cross text-primary" style="font-size: 64px;"></i>
                                <h3 class="mt-2">Challenge Invitation</h3>
                                <p class="text-muted">
                                    <strong>{{ $challenge->challenger->full_name }}</strong> has challenged you to a coding duel!
                                </p>
                            </div>
                            
                            <div class="d-flex justify-content-center">
                                <form action="{{ route('student.challenges.accept', $challenge->id) }}" method="POST" class="me-2">
                                    @csrf
                                    <button type="submit" class="btn btn-lg btn-primary">
                                        Accept Challenge
                                    </button>
                                </form>
                                
                                <form action="{{ route('student.challenges.decline', $challenge->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-lg btn-outline-danger">
                                        Decline
                                    </button>
                                </form>
                            </div>
                            
                            <div class="mt-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Challenge Details</h5>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <td width="150"><strong>Category:</strong></td>
                                                    <td>{{ $challenge->category->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Questions:</strong></td>
                                                    <td>{{ $totalQuestions }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Difficulty:</strong></td>
                                                    <td>{{ ucfirst($challenge->difficulty ?? 'Mixed') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Sent:</strong></td>
                                                    <td>{{ $challenge->created_at->diffForHumans() }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- If the challenge is active -->
                    @if($challenge->status == 'active')
                        <div id="challenge-question-container">
                            <!-- Question -->
                            <div class="question-container p-4 border rounded mb-4">
                                <h4 class="mb-3">{{ $currentQuestion->question_text }}</h4>
                                
                                <!-- Timer -->
                                <div class="timer-container mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">Time remaining:</div>
                                        <div id="timer" class="badge bg-warning px-3 py-2" data-seconds="{{ $currentQuestion->time_allowed }}">
                                            {{ $currentQuestion->time_allowed }} sec
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Answer Form -->
                                <form id="answer-form" action="{{ route('student.challenges.submit-answer', $challenge->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="question_id" value="{{ $currentQuestion->id }}">
                                    <input type="hidden" name="time_taken" id="time-taken" value="0">
                                    
                                    <div class="list-group">
                                        <button type="button" class="answer-option list-group-item list-group-item-action p-3 mb-2" data-answer="A" onclick="selectAnswer('A')">
                                            <strong class="me-2">A.</strong> {{ $currentQuestion->option_a }}
                                        </button>
                                        <button type="button" class="answer-option list-group-item list-group-item-action p-3 mb-2" data-answer="B" onclick="selectAnswer('B')">
                                            <strong class="me-2">B.</strong> {{ $currentQuestion->option_b }}
                                        </button>
                                        <button type="button" class="answer-option list-group-item list-group-item-action p-3 mb-2" data-answer="C" onclick="selectAnswer('C')">
                                            <strong class="me-2">C.</strong> {{ $currentQuestion->option_c }}
                                        </button>
                                        <button type="button" class="answer-option list-group-item list-group-item-action p-3 mb-2" data-answer="D" onclick="selectAnswer('D')">
                                            <strong class="me-2">D.</strong> {{ $currentQuestion->option_d }}
                                        </button>
                                    </div>
                                    
                                    <input type="hidden" name="selected_answer" id="selected-answer" value="">
                                    
                                    <div class="text-end mt-3">
                                        <button type="submit" id="submit-answer" class="btn btn-primary" disabled>
                                            Submit Answer
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Question Navigation -->
                        <div class="d-flex justify-content-between mt-3">
                            <div>
                                @if($currentQuestionIndex > 0)
                                    <a href="{{ route('student.challenges.show', ['challenge' => $challenge->id, 'question' => $currentQuestionIndex]) }}" class="btn btn-outline-primary">
                                        <i class="mdi mdi-arrow-left"></i> Previous
                                    </a>
                                @else
                                    <button class="btn btn-outline-primary" disabled>
                                        <i class="mdi mdi-arrow-left"></i> Previous
                                    </button>
                                @endif
                            </div>
                            <div>
                                <span class="px-2">
                                    Question {{ $currentQuestionIndex + 1 }} of {{ $totalQuestions }}
                                </span>
                            </div>
                            <div>
                                @if($currentQuestionIndex < $totalQuestions - 1)
                                    <button id="next-question" class="btn btn-outline-primary" disabled>
                                        Next <i class="mdi mdi-arrow-right"></i>
                                    </button>
                                @else
                                    <button id="finish-challenge" class="btn btn-success" disabled>
                                        Finish Challenge <i class="mdi mdi-check"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- If the challenge is completed -->
                    @if($challenge->status == 'completed')
                        <div class="text-center py-5">
                            <i class="mdi mdi-check-circle-outline text-success" style="font-size: 64px;"></i>
                            <h3 class="mt-2">Challenge Completed</h3>
                            <p class="text-muted">
                                This challenge has been completed. View the results to see how you did!
                            </p>
                            <div class="mt-3">
                                <a href="{{ route('student.challenges.result', $challenge->id) }}" class="btn btn-primary">
                                    View Results
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Variables to track timer
    let timeAllowed = parseInt($('#timer').data('seconds'));
    let timeRemaining = timeAllowed;
    let timerInterval;
    let startTime = new Date().getTime();
    
    // Start timer when page loads
    $(document).ready(function() {
        startTimer();
        
        // Submit form when an answer is selected and confirmed
        $('#answer-form').on('submit', function(e) {
            e.preventDefault();
            
            // Calculate time taken
            const endTime = new Date().getTime();
            const timeTaken = Math.min(timeAllowed - timeRemaining, timeAllowed);
            $('#time-taken').val(timeTaken);
            
            // Stop timer
            clearInterval(timerInterval);
            
            // Disable all answer buttons
            $('.answer-option').prop('disabled', true);
            
            // Show loading state on submit button
            $('#submit-answer').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...').prop('disabled', true);
            
            // Submit the form using AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        // Highlight the correct answer
                        $('.answer-option[data-answer="' + response.correct_answer + '"]')
                            .removeClass('list-group-item-primary')
                            .addClass('list-group-item-success');
                        
                        // If user selected wrong answer, highlight it red
                        if (response.selected_answer !== response.correct_answer) {
                            $('.answer-option[data-answer="' + response.selected_answer + '"]')
                                .removeClass('list-group-item-primary')
                                .addClass('list-group-item-danger');
                        }
                        
                        // Enable navigation buttons
                        $('#next-question, #finish-challenge').prop('disabled', false);
                        
                        // Show explanation if available
                        if (response.explanation) {
                            $('<div class="alert alert-info mt-3">' + 
                              '<h5>Explanation:</h5>' + 
                              '<p>' + response.explanation + '</p>' + 
                              '</div>').insertAfter('.question-container');
                        }
                        
                        // Restore button text
                        $('#submit-answer').html('Answer Submitted').prop('disabled', true);
                    }
                },
                error: function() {
                    // Handle error
                    alert('An error occurred while submitting your answer. Please try again.');
                    $('#submit-answer').html('Submit Answer').prop('disabled', false);
                    $('.answer-option').prop('disabled', false);
                    startTimer();
                }
            });
        });
    });
    
    function startTimer() {
        timerInterval = setInterval(function() {
            timeRemaining--;
            $('#timer').text(timeRemaining + ' sec');
            
            // Change timer color based on time remaining
            if (timeRemaining <= 10) {
                $('#timer').removeClass('bg-warning').addClass('bg-danger');
            }
            
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                timeOut();
            }
        }, 1000);
    }
    
    function timeOut() {
        // Auto-submit the form when time runs out
        $('#selected-answer').val('TIMEOUT');
        $('#submit-answer').click();
    }
    
    function selectAnswer(answer) {
        // Clear previous selection
        $('.answer-option').removeClass('list-group-item-primary');
        
        // Highlight selected answer
        $('.answer-option[data-answer="' + answer + '"]').addClass('list-group-item-primary');
        
        // Update hidden input
        $('#selected-answer').val(answer);
        
        // Enable submit button
        $('#submit-answer').prop('disabled', false);
    }
</script>
@endsection
