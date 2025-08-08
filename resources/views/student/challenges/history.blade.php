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
                <h4 class="page-title">Challenge History</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Your Challenge History</h4>
                    <p class="text-muted">View all your past challenges and results</p>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-10">
                            <form action="{{ route('student.challenges.history') }}" method="GET" class="row g-2">
                                <div class="col-md-3">
                                    <select name="category" class="form-select" onchange="this.form.submit()">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="result" class="form-select" onchange="this.form.submit()">
                                        <option value="">All Results</option>
                                        <option value="won" {{ request('result') == 'won' ? 'selected' : '' }}>Won</option>
                                        <option value="lost" {{ request('result') == 'lost' ? 'selected' : '' }}>Lost</option>
                                        <option value="draw" {{ request('result') == 'draw' ? 'selected' : '' }}>Draw</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="period" class="form-select" onchange="this.form.submit()">
                                        <option value="">All Time</option>
                                        <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Last Week</option>
                                        <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Last Month</option>
                                        <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>Last Year</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="mdi mdi-filter"></i> Filter
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-2 text-md-end mt-2 mt-md-0">
                            <a href="{{ route('student.challenges.history') }}" class="btn btn-outline-secondary">
                                <i class="mdi mdi-refresh"></i> Reset
                            </a>
                        </div>
                    </div>

                    <!-- Challenge History Table -->
                    @if($challenges->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-centered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Category</th>
                                        <th>Challenger</th>
                                        <th>Opponent</th>
                                        <th>Score</th>
                                        <th>Result</th>
                                        <th>XP Earned</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($challenges as $challenge)
                                        @php
                                            $currentStudent = auth()->user()->student;
                                            $isWinner = ($challenge->result && $challenge->result->winner_id == $currentStudent->id);
                                            $isDraw = ($challenge->result && $challenge->result->is_draw);
                                            
                                            $resultClass = $isDraw ? 'secondary' : ($isWinner ? 'success' : 'danger');
                                            $resultText = $isDraw ? 'Draw' : ($isWinner ? 'Won' : 'Lost');
                                        @endphp
                                        <tr>
                                            <td>{{ $challenge->completed_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <span class="badge" style="background-color: {{ $challenge->category->color }}">
                                                    <i class="{{ $challenge->category->icon }}"></i> 
                                                    {{ $challenge->category->name }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $challenge->challenger->full_name }}
                                                @if($challenge->challenger_id == $currentStudent->id)
                                                    <span class="badge bg-soft-primary text-primary">You</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $challenge->opponent->full_name ?? 'Random Opponent' }}
                                                @if($challenge->opponent_id == $currentStudent->id)
                                                    <span class="badge bg-soft-primary text-primary">You</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($challenge->challenger_id == $currentStudent->id)
                                                    <strong>{{ $challenge->result->challenger_score }}</strong> - {{ $challenge->result->opponent_score }}
                                                @else
                                                    {{ $challenge->result->challenger_score }} - <strong>{{ $challenge->result->opponent_score }}</strong>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $resultClass }}">{{ $resultText }}</span>
                                            </td>
                                            <td>
                                                <span class="text-success">+{{ $challenge->result->xp_earned ?? 0 }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('student.challenges.result', $challenge->id) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="mdi mdi-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="row mt-4">
                            <div class="col-12">
                                {{ $challenges->appends(request()->except('page'))->links() }}
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="mdi mdi-information-outline mr-2"></i>
                            No challenge history found for the selected filters. Try resetting the filters or create a new challenge!
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Challenge Statistics -->
    <div class="row">
        <!-- Challenge by Category -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Challenges by Category</h4>
                    <p class="text-muted">Your performance across different categories</p>
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-centered">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Played</th>
                                    <th>Won</th>
                                    <th>Lost</th>
                                    <th>Win Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categoryStats as $stat)
                                    <tr>
                                        <td>
                                            <span class="badge" style="background-color: {{ $stat['category']->color }}">
                                                <i class="{{ $stat['category']->icon }}"></i> 
                                                {{ $stat['category']->name }}
                                            </span>
                                        </td>
                                        <td>{{ $stat['total'] }}</td>
                                        <td class="text-success">{{ $stat['won'] }}</td>
                                        <td class="text-danger">{{ $stat['lost'] }}</td>
                                        <td>
                                            @if($stat['total'] > 0)
                                                {{ round(($stat['won'] / $stat['total']) * 100) }}%
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Monthly Performance -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Monthly Performance</h4>
                    <p class="text-muted">Your challenge activity over time</p>
                    
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="monthlyChart"></canvas>
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
        // Monthly performance chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        
        // Sample data - in a real application, this would come from your controller
        const monthlyData = {
            labels: {!! json_encode($monthlyStats['labels']) !!},
            datasets: [
                {
                    label: 'Challenges Won',
                    backgroundColor: '#28a745',
                    borderColor: '#28a745',
                    data: {!! json_encode($monthlyStats['won']) !!}
                },
                {
                    label: 'Challenges Lost',
                    backgroundColor: '#dc3545',
                    borderColor: '#dc3545',
                    data: {!! json_encode($monthlyStats['lost']) !!}
                },
                {
                    label: 'XP Earned',
                    backgroundColor: '#950713',
                    borderColor: '#950713',
                    data: {!! json_encode($monthlyStats['xp']) !!},
                    type: 'line',
                    yAxisID: 'y1'
                }
            ]
        };
        
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'bar',
            data: monthlyData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Challenges'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'XP'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    });
</script>
@endsection

@section('styles')
<style>
    .badge {
        padding: 5px 10px;
    }
    
    /* Primary color customization for IMT */
    .btn-primary, .bg-primary, .badge-primary, .text-primary, .border-primary {
        background-color: #950713 !important;
        border-color: #950713 !important;
    }
    
    .text-primary {
        color: #950713 !important;
    }
    
    .btn-outline-primary {
        color: #950713 !important;
        border-color: #950713 !important;
    }
    
    .btn-outline-primary:hover {
        background-color: #950713 !important;
        color: #fff !important;
    }
</style>
@endsection
