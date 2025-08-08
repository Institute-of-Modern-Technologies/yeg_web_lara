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
                <h4 class="page-title">Challenge Results</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <!-- Challenge Summary Card -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div>
                            <h4 class="card-title mb-1">
                                <span class="badge" style="background-color: {{ $challenge->category->color }}">
                                    <i class="{{ $challenge->category->icon }}"></i> 
                                    {{ $challenge->category->name }} Challenge
                                </span>
                            </h4>
                            <p class="text-muted mb-0">Completed {{ $challenge->completed_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <!-- Challenge Result -->
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg me-3">
                                    <div class="avatar-title bg-light text-{{ $challenge->result && $challenge->result->winner_id == auth()->user()->student->id ? 'success' : 'secondary' }} rounded-circle">
                                        @php
                                            $currentStudent = auth()->user()->student;
                                            $isWinner = ($challenge->result && $challenge->result->winner_id == $currentStudent->id);
                                            $isDraw = ($challenge->result && $challenge->result->is_draw);
                                        @endphp
                                        
                                        @if($isDraw)
                                            <i class="mdi mdi-equal-box font-24"></i>
                                        @elseif($isWinner)
                                            <i class="mdi mdi-trophy font-24"></i>
                                        @else
                                            <i class="mdi mdi-close-circle-outline font-24"></i>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    @if($isDraw)
                                        <h3 class="mb-1 text-secondary">It's a Draw!</h3>
                                        <p class="text-muted mb-0">Both players earned equal points</p>
                                    @elseif($isWinner)
                                        <h3 class="mb-1 text-success">You Won!</h3>
                                        <p class="text-muted mb-0">Congratulations on your victory!</p>
                                    @else
                                        <h3 class="mb-1 text-danger">You Lost</h3>
                                        <p class="text-muted mb-0">Better luck next time!</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <div class="d-flex justify-content-md-end">
                                <div class="me-4 text-center">
                                    <h4>{{ $challenge->result->challenger_score ?? 0 }}</h4>
                                    <p class="text-muted mb-0">
                                        {{ $challenge->challenger->full_name }}
                                        @if($challenge->challenger_id == $currentStudent->id)
                                            <span class="badge bg-soft-primary text-primary">You</span>
                                        @endif
                                    </p>
                                </div>
                                
                                <div class="text-center">
                                    <h4>{{ $challenge->result->opponent_score ?? 0 }}</h4>
                                    <p class="text-muted mb-0">
                                        {{ $challenge->opponent->full_name ?? 'Opponent' }}
                                        @if($challenge->opponent_id == $currentStudent->id)
                                            <span class="badge bg-soft-primary text-primary">You</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    
                    <!-- XP and Rewards -->
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="py-3">
                                <h3 class="text-success">+{{ $challenge->result->xp_earned ?? 0 }}</h3>
                                <p class="text-muted mb-0">XP Earned</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="py-3">
                                <h3>{{ $challenge->result->correct_answers ?? 0 }}/{{ count($challenge->questions) }}</h3>
                                <p class="text-muted mb-0">Correct Answers</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="py-3">
                                <h3>{{ $challenge->result->avg_time ?? 0 }}s</h3>
                                <p class="text-muted mb-0">Avg. Response Time</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Question Results -->
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Question Details</h4>

                    <div class="accordion" id="questionAccordion">
                        @foreach($challenge->questions as $index => $question)
                            @php
                                $response = $challenge->responses->where('question_id', $question->id)
                                    ->where('student_id', $currentStudent->id)
                                    ->first();
                                
                                $isCorrect = $response && $response->selected_answer == $question->correct_answer;
                                $answerClass = $response ? ($isCorrect ? 'success' : 'danger') : 'warning';
                                $icon = $response ? ($isCorrect ? 'mdi-check-circle' : 'mdi-close-circle') : 'mdi-alert-circle';
                            @endphp
                            
                            <div class="card mb-2">
                                <div class="card-header bg-light" id="heading{{ $index }}">
                                    <h5 class="m-0">
                                        <a class="text-dark collapsed d-block" data-toggle="collapse" 
                                           href="#collapse{{ $index }}" aria-expanded="false">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="mdi {{ $icon }} text-{{ $answerClass }} me-1"></i>
                                                    Question {{ $index + 1 }}
                                                </div>
                                                <div class="text-{{ $answerClass }}">
                                                    @if($response)
                                                        {{ $isCorrect ? 'Correct' : 'Incorrect' }} 
                                                        ({{ $response->time_taken ?? 0 }}s)
                                                    @else
                                                        Not Answered
                                                    @endif
                                                    <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapse{{ $index }}" class="collapse" aria-labelledby="heading{{ $index }}" 
                                     data-parent="#questionAccordion">
                                    <div class="card-body">
                                        <h5>{{ $question->question_text }}</h5>
                                        
                                        <div class="list-group mt-3">
                                            <div class="list-group-item {{ $question->correct_answer == 'A' ? 'list-group-item-success' : 
                                                 ($response && $response->selected_answer == 'A' ? 'list-group-item-danger' : '') }}">
                                                <strong>A.</strong> {{ $question->option_a }}
                                                @if($question->correct_answer == 'A')
                                                    <i class="mdi mdi-check-circle float-end text-success"></i>
                                                @endif
                                            </div>
                                            <div class="list-group-item {{ $question->correct_answer == 'B' ? 'list-group-item-success' : 
                                                 ($response && $response->selected_answer == 'B' ? 'list-group-item-danger' : '') }}">
                                                <strong>B.</strong> {{ $question->option_b }}
                                                @if($question->correct_answer == 'B')
                                                    <i class="mdi mdi-check-circle float-end text-success"></i>
                                                @endif
                                            </div>
                                            <div class="list-group-item {{ $question->correct_answer == 'C' ? 'list-group-item-success' : 
                                                 ($response && $response->selected_answer == 'C' ? 'list-group-item-danger' : '') }}">
                                                <strong>C.</strong> {{ $question->option_c }}
                                                @if($question->correct_answer == 'C')
                                                    <i class="mdi mdi-check-circle float-end text-success"></i>
                                                @endif
                                            </div>
                                            <div class="list-group-item {{ $question->correct_answer == 'D' ? 'list-group-item-success' : 
                                                 ($response && $response->selected_answer == 'D' ? 'list-group-item-danger' : '') }}">
                                                <strong>D.</strong> {{ $question->option_d }}
                                                @if($question->correct_answer == 'D')
                                                    <i class="mdi mdi-check-circle float-end text-success"></i>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($question->explanation)
                                            <div class="alert alert-info mt-3">
                                                <h5>Explanation:</h5>
                                                <p>{{ $question->explanation }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- Challenge Actions -->
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Actions</h4>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('student.challenges.create', ['category' => $challenge->category_id]) }}" class="btn btn-primary">
                            <i class="mdi mdi-sword"></i> New Challenge in Same Category
                        </a>
                        
                        @if($challenge->challenger_id != $currentStudent->id)
                            <a href="{{ route('student.challenges.create', ['opponent' => $challenge->challenger_id]) }}" class="btn btn-info">
                                <i class="mdi mdi-account-multiple"></i> Challenge {{ $challenge->challenger->full_name }} Again
                            </a>
                        @endif
                        
                        @if($challenge->opponent_id != $currentStudent->id && $challenge->opponent_id)
                            <a href="{{ route('student.challenges.create', ['opponent' => $challenge->opponent_id]) }}" class="btn btn-info">
                                <i class="mdi mdi-account-multiple"></i> Challenge {{ $challenge->opponent->full_name }} Again
                            </a>
                        @endif
                        
                        <a href="{{ route('student.challenges.index') }}" class="btn btn-outline-primary">
                            <i class="mdi mdi-format-list-bulleted"></i> Back to Challenges
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Challenge Stats -->
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Challenge Statistics</h4>
                    
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td>{{ $challenge->category->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Questions:</strong></td>
                                    <td>{{ count($challenge->questions) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Difficulty:</strong></td>
                                    <td>{{ ucfirst($challenge->difficulty ?? 'Mixed') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $challenge->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Completed:</strong></td>
                                    <td>{{ $challenge->completed_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Duration:</strong></td>
                                    <td>{{ $challenge->created_at->diffInMinutes($challenge->completed_at) }} minutes</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Performance Chart -->
                    <div class="mt-3">
                        <h5>Performance</h5>
                        <div class="chart-container" style="height: 200px;">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Bootstrap accordion functionality
        $('.collapse').on('show.bs.collapse', function () {
            $(this).closest('.card').find('.accordion-arrow')
                .removeClass('mdi-chevron-down')
                .addClass('mdi-chevron-up');
        });
        
        $('.collapse').on('hide.bs.collapse', function () {
            $(this).closest('.card').find('.accordion-arrow')
                .removeClass('mdi-chevron-up')
                .addClass('mdi-chevron-down');
        });
        
        // Performance Chart
        const ctx = document.getElementById('performanceChart').getContext('2d');
        
        // Sample data - in a real application, this would come from your controller
        const performanceData = {
            labels: ['Correct Answers', 'Speed Bonus', 'Difficulty Bonus'],
            datasets: [{
                label: 'Points',
                backgroundColor: ['#28a745', '#17a2b8', '#950713'],
                borderColor: '#fff',
                borderWidth: 1,
                data: [
                    {{ $challenge->result->correct_answers ?? 0 }} * 10, // Base points for correct answers
                    {{ $challenge->result->speed_bonus ?? 0 }},         // Speed bonus
                    {{ $challenge->result->difficulty_bonus ?? 0 }}      // Difficulty bonus
                ]
            }]
        };
        
        const performanceChart = new Chart(ctx, {
            type: 'bar',
            data: performanceData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection
