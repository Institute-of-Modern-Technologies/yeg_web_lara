@extends('layouts.student-unified')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{ route('student.challenges.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> New Challenge
                    </a>
                </div>
                <h4 class="page-title">Coding Challenges</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Challenge Stats -->
        <div class="col-xl-3 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Your Stats</h4>
                    <div class="widget-chart text-center">
                        <div class="mt-3">
                            <h3>{{ $stats->total_xp ?? 0 }} XP</h3>
                            <p class="text-muted mb-0">Total Experience Points</p>
                        </div>
                        <div class="row mt-3">
                            <div class="col-4">
                                <h3>{{ $stats->challenges_won ?? 0 }}</h3>
                                <p class="text-muted mb-0">Won</p>
                            </div>
                            <div class="col-4">
                                <h3>{{ $stats->challenges_lost ?? 0 }}</h3>
                                <p class="text-muted mb-0">Lost</p>
                            </div>
                            <div class="col-4">
                                <h3>{{ $stats->challenges_total ?? 0 }}</h3>
                                <p class="text-muted mb-0">Total</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Challenge Categories -->
        <div class="col-xl-5 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Challenge Categories</h4>
                    <div class="row">
                        @forelse($categories as $category)
                            <div class="col-md-4 col-6 mb-3">
                                <a href="{{ route('student.challenges.create', ['category' => $category->id]) }}" 
                                   class="text-center d-block category-card"
                                   style="border-left: 4px solid {{ $category->color }}">
                                    <div class="p-2">
                                        <i class="{{ $category->icon }} fa-2x" style="color: {{ $category->color }}"></i>
                                        <h5 class="mt-2 mb-0">{{ $category->name }}</h5>
                                        <small class="text-muted">
                                            {{ $category->questions_count ?? 0 }} questions
                                        </small>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">
                                    No challenge categories available at the moment.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Leaderboard -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Leaderboard</h4>
                    <div class="table-responsive">
                        <table class="table table-sm table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Student</th>
                                    <th>XP</th>
                                    <th>W/L</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaderboard as $index => $stat)
                                    <tr class="{{ ($stat->student_id && auth()->user()->student && $stat->student_id == auth()->user()->student->id) ? 'table-primary' : '' }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $stat->student ? $stat->student->full_name : 'Unknown' }}</td>
                                        <td>{{ $stat->total_xp ?? 0 }}</td>
                                        <td>{{ $stat->challenges_won ?? 0 }}/{{ $stat->challenges_lost ?? 0 }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Challenges -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Pending Challenges</h4>
                    <p class="text-muted">Challenges waiting for your response</p>

                    @if($pendingChallenges->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>Challenger</th>
                                        <th>Category</th>
                                        <th>Sent On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingChallenges as $challenge)
                                        <tr>
                                            <td>{{ $challenge->challenger->full_name ?? 'Unknown' }}</td>
                                            <td>
                                                <span class="badge" style="background-color: {{ $challenge->category->color }}">
                                                    <i class="{{ $challenge->category->icon }}"></i> 
                                                    {{ $challenge->category->name }}
                                                </span>
                                            </td>
                                            <td>{{ $challenge->created_at->diffForHumans() }}</td>
                                            <td>
                                                <a href="{{ route('student.challenges.show', $challenge->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    Accept Challenge
                                                </a>
                                                <form action="{{ route('student.challenges.decline', $challenge->id) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to decline this challenge?')">
                                                        Decline
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No pending challenges at the moment.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Active Challenges -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Active Challenges</h4>
                    <p class="text-muted">Ongoing challenges</p>

                    @if($activeChallenges->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>Challenger</th>
                                        <th>Opponent</th>
                                        <th>Category</th>
                                        <th>Created</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeChallenges as $challenge)
                                        <tr>
                                            <td>{{ $challenge->challenger->full_name ?? 'Unknown' }}</td>
                                            <td>{{ $challenge->opponent->full_name ?? 'Random Opponent' }}</td>
                                            <td>
                                                <span class="badge" style="background-color: {{ $challenge->category->color }}">
                                                    <i class="{{ $challenge->category->icon }}"></i> 
                                                    {{ $challenge->category->name }}
                                                </span>
                                            </td>
                                            <td>{{ $challenge->created_at->diffForHumans() }}</td>
                                            <td>
                                                @if($challenge->challenger_id == auth()->user()->student->id)
                                                    <span class="badge bg-primary">Your Turn</span>
                                                @else
                                                    <span class="badge bg-warning">Opponent's Turn</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('student.challenges.show', $challenge->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    Continue
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No active challenges at the moment.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Completed Challenges -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h4 class="header-title mb-0">Recent Completed Challenges</h4>
                            <p class="text-muted">Your recently completed challenge matches</p>
                        </div>
                        <a href="{{ route('student.challenges.history') }}" class="btn btn-sm btn-outline-primary">
                            View All History
                        </a>
                    </div>

                    @if($completedChallenges->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>Challenger</th>
                                        <th>Opponent</th>
                                        <th>Category</th>
                                        <th>Completed</th>
                                        <th>Result</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completedChallenges as $challenge)
                                        <tr>
                                            <td>{{ $challenge->challenger->full_name ?? 'Unknown' }}</td>
                                            <td>{{ $challenge->opponent->full_name ?? 'Random Opponent' }}</td>
                                            <td>
                                                <span class="badge" style="background-color: {{ $challenge->category->color }}">
                                                    <i class="{{ $challenge->category->icon }}"></i> 
                                                    {{ $challenge->category->name }}
                                                </span>
                                            </td>
                                            <td>{{ $challenge->completed_at->diffForHumans() }}</td>
                                            <td>
                                                @php
                                                    $currentStudent = auth()->user()->student;
                                                    $isWinner = ($challenge->result && $challenge->result->winner_id == $currentStudent->id);
                                                    $isDraw = ($challenge->result && $challenge->result->is_draw);
                                                @endphp
                                                
                                                @if($isDraw)
                                                    <span class="badge bg-secondary">Draw</span>
                                                @elseif($isWinner)
                                                    <span class="badge bg-success">Won</span>
                                                @else
                                                    <span class="badge bg-danger">Lost</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('student.challenges.result', $challenge->id) }}" 
                                                   class="btn btn-sm btn-info">
                                                    View Result
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No completed challenges yet.
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
    $(document).ready(function() {
        // Add any JavaScript needed for the challenges page here
    });
</script>
@endsection

@section('styles')
<style>
    .category-card {
        transition: all 0.3s ease;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        text-decoration: none;
    }
</style>
@endsection
