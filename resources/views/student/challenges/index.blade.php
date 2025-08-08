@extends('layouts.student-unified')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Gamified Header -->
    <div class="relative mb-8 bg-gradient-to-r from-purple-600 via-blue-600 to-teal-600 rounded-2xl p-8 text-white overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-black bg-opacity-10">
            <div class="absolute top-4 right-4 text-6xl opacity-20">
                <i class="fas fa-code"></i>
            </div>
            <div class="absolute bottom-4 left-4 text-4xl opacity-20">
                <i class="fas fa-trophy"></i>
            </div>
        </div>
        
        <div class="relative z-10 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold mb-2 flex items-center">
                    <i class="fas fa-sword mr-3 text-yellow-300"></i>
                    Battle Arena
                </h1>
                <p class="text-blue-100 text-lg">⚡ Challenge friends • 🏆 Earn XP • 🔥 Climb leaderboards</p>
                <div class="flex items-center mt-3 space-x-4">
                    <div class="flex items-center bg-white bg-opacity-20 rounded-full px-3 py-1">
                        <i class="fas fa-fire text-orange-300 mr-2"></i>
                        <span class="font-semibold">0 Day Streak</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-full px-3 py-1">
                        <i class="fas fa-star text-yellow-300 mr-2"></i>
                        <span class="font-semibold">Level 1</span>
                    </div>
                </div>
            </div>
            <div>
                <button onclick="window.location.href='{{ route('student.challenges.create') }}'" class="bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-500 hover:to-orange-600 text-white px-6 py-3 rounded-xl font-bold text-lg shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Start Battle!
                </button>
            </div>
        </div>
    </div>

    <!-- Gamified Stats and Categories Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- XP & Level Progress -->
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 text-8xl opacity-10">
                <i class="fas fa-gem"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">🏆 Your Progress</h3>
                    <div class="bg-white bg-opacity-20 rounded-full px-3 py-1 text-sm font-semibold">
                        Level 1
                    </div>
                </div>
                
                <div class="text-center mb-4">
                    <div class="text-4xl font-black mb-2">
                        {{ $stats->total_xp ?? 0 }} <span class="text-2xl font-bold text-yellow-300">XP</span>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full h-3 mb-2">
                        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 h-3 rounded-full" style="width: {{ min(100, (($stats->total_xp ?? 0) / 100) * 100) }}%"></div>
                    </div>
                    <p class="text-indigo-100 text-sm">{{ 100 - ($stats->total_xp ?? 0) }} XP to Level 2</p>
                </div>
                
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white bg-opacity-20 rounded-xl p-3 text-center">
                        <div class="text-2xl font-bold text-green-300">{{ $stats->challenges_won ?? 0 }}</div>
                        <div class="text-xs text-green-100">🎯 Victories</div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-xl p-3 text-center">
                        <div class="text-2xl font-bold text-red-300">{{ $stats->challenges_lost ?? 0 }}</div>
                        <div class="text-xs text-red-100">💥 Defeats</div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-xl p-3 text-center">
                        <div class="text-2xl font-bold text-blue-300">{{ $stats->challenges_total ?? 0 }}</div>
                        <div class="text-xs text-blue-100">⚔️ Battles</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Battle Categories -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
            <div class="flex items-center mb-6">
                <div class="bg-gradient-to-r from-pink-500 to-rose-500 rounded-full p-2 mr-3">
                    <i class="fas fa-gamepad text-white text-lg"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">⚔️ Choose Your Battle</h3>
            </div>
            
            @if($categories && $categories->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($categories as $category)
                        <div class="group relative bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6 text-center hover:from-blue-100 hover:to-indigo-100 hover:border-blue-400 hover:shadow-xl transform hover:scale-105 transition-all duration-300 cursor-pointer">
                            <div class="absolute top-2 right-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full">
                                NEW
                            </div>
                            
                            <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 group-hover:from-blue-600 group-hover:to-purple-700 transition-all duration-300">
                                <i class="{{ $category ? $category->icon : 'fas fa-code' }} text-2xl text-white"></i>
                            </div>
                            
                            <h4 class="font-bold text-gray-800 mb-2 text-lg">{{ $category ? $category->name : 'Unknown Category' }}</h4>
                            <p class="text-gray-600 text-sm mb-4">{{ $category ? $category->description : 'No description available' }}</p>
                            
                            <div class="flex items-center justify-center space-x-4 text-xs">
                                <div class="flex items-center text-green-600">
                                    <i class="fas fa-star mr-1"></i>
                                    <span class="font-semibold">Easy</span>
                                </div>
                                <div class="flex items-center text-orange-600">
                                    <i class="fas fa-fire mr-1"></i>
                                    <span class="font-semibold">+50 XP</span>
                                </div>
                                <div class="flex items-center text-purple-600">
                                    <i class="fas fa-users mr-1"></i>
                                    <span class="font-semibold">0 Active</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-dashed border-gray-300">
                    <div class="bg-gradient-to-br from-gray-400 to-gray-500 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-gamepad text-3xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-600 mb-2">No Battle Arenas Yet!</h4>
                    <p class="text-gray-500">New challenge categories are coming soon...</p>
                    <div class="mt-4">
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                            🚀 Stay tuned for epic battles!
                        </span>
                    </div>
                </div>
            @endif
        </div>

    </div>

    <!-- Hall of Fame Leaderboard -->
    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl shadow-xl p-6 mb-8 border border-yellow-200">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full p-2 mr-3">
                    <i class="fas fa-crown text-white text-lg"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">🏆 Hall of Fame</h3>
            </div>
            <div class="bg-white bg-opacity-70 rounded-full px-4 py-2 text-sm font-semibold text-gray-700">
                🔥 Live Rankings
            </div>
        </div>
        
        @forelse($leaderboard as $index => $stat)
            @php
                $isCurrentUser = ($stat->student_id && auth()->user() && auth()->user()->student && $stat->student_id == auth()->user()->student->id);
                $rank = $index + 1;
                $rankColor = $rank == 1 ? 'from-yellow-400 to-yellow-600' : ($rank == 2 ? 'from-gray-300 to-gray-500' : ($rank == 3 ? 'from-orange-400 to-orange-600' : 'from-blue-400 to-blue-600'));
                $rankIcon = $rank == 1 ? 'fas fa-crown' : ($rank == 2 ? 'fas fa-medal' : ($rank == 3 ? 'fas fa-award' : 'fas fa-user'));
                $bgClass = $isCurrentUser ? 'bg-gradient-to-r from-blue-100 to-indigo-100 border-2 border-blue-300' : 'bg-white bg-opacity-60 border border-gray-200';
            @endphp
            
            <div class="{{ $bgClass }} rounded-xl p-4 mb-3 hover:shadow-lg transition-all duration-300 {{ $isCurrentUser ? 'transform scale-105' : '' }}">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Rank Badge -->
                        <div class="bg-gradient-to-br {{ $rankColor }} rounded-full w-12 h-12 flex items-center justify-center text-white font-bold shadow-lg">
                            @if($rank <= 3)
                                <i class="{{ $rankIcon }} text-lg"></i>
                            @else
                                <span class="text-lg">#{{ $rank }}</span>
                            @endif
                        </div>
                        
                        <!-- Player Info -->
                        <div>
                            <div class="flex items-center">
                                <h4 class="font-bold text-gray-800 {{ $isCurrentUser ? 'text-blue-800' : '' }}">
                                    {{ ($stat->student && $stat->student->full_name) ? $stat->student->full_name : 'Unknown Warrior' }}
                                </h4>
                                @if($isCurrentUser)
                                    <span class="ml-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        YOU
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center space-x-3 mt-1">
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-fire text-orange-500 mr-1"></i>
                                    {{ $stat->total_xp ?? 0 }} XP
                                </span>
                                <span class="text-sm text-green-600">
                                    <i class="fas fa-trophy mr-1"></i>
                                    {{ $stat->challenges_won ?? 0 }}W
                                </span>
                                <span class="text-sm text-red-500">
                                    <i class="fas fa-times mr-1"></i>
                                    {{ $stat->challenges_lost ?? 0 }}L
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Win Rate -->
                    <div class="text-right">
                        @php
                            $total = ($stat->challenges_won ?? 0) + ($stat->challenges_lost ?? 0);
                            $winRate = $total > 0 ? round((($stat->challenges_won ?? 0) / $total) * 100) : 0;
                        @endphp
                        <div class="text-2xl font-bold {{ $winRate >= 70 ? 'text-green-600' : ($winRate >= 50 ? 'text-yellow-600' : 'text-red-500') }}">
                            {{ $winRate }}%
                        </div>
                        <div class="text-xs text-gray-500">Win Rate</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white bg-opacity-60 rounded-2xl border-2 border-dashed border-gray-300">
                <div class="bg-gradient-to-br from-gray-400 to-gray-500 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-2xl text-white"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-600 mb-2">No Champions Yet!</h4>
                <p class="text-gray-500">Be the first to earn your place in the Hall of Fame</p>
                <div class="mt-4">
                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                        🎆 Start your legend today!
                    </span>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pending Challenges -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Pending Challenges</h3>
        <p class="text-gray-600 mb-4">Challenges waiting for your response</p>

        @if($pendingChallenges->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Challenger</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sent On</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingChallenges as $challenge)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ($challenge->challenger && $challenge->challenger->full_name) ? $challenge->challenger->full_name : 'Unknown' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($challenge->category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white" style="background-color: {{ $challenge->category->color ?? '#950713' }}">
                                            <i class="{{ $challenge->category->icon ?? 'fas fa-code' }} mr-1"></i> 
                                            {{ $challenge->category->name ?? 'Unknown' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-500 text-white">Unknown Category</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $challenge->created_at ? $challenge->created_at->diffForHumans() : 'Unknown' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('student.challenges.show', $challenge->id) }}" 
                                       class="bg-primary hover:bg-red-800 text-white px-3 py-1 rounded text-sm transition-colors">
                                        Accept Challenge
                                    </a>
                                    <form action="{{ route('student.challenges.decline', $challenge->id) }}" 
                                          method="POST" 
                                          class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition-colors"
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
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-blue-800">No pending challenges at the moment.</p>
            </div>
        @endif
    </div>

    <!-- Active Challenges -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Active Challenges</h3>
        <p class="text-gray-600 mb-4">Ongoing challenges</p>

        @if($activeChallenges->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Challenger</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opponent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($activeChallenges as $challenge)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ($challenge->challenger && $challenge->challenger->full_name) ? $challenge->challenger->full_name : 'Unknown' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ($challenge->opponent && $challenge->opponent->full_name) ? $challenge->opponent->full_name : 'Random Opponent' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($challenge->category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white" style="background-color: {{ $challenge->category->color ?? '#950713' }}">
                                            <i class="{{ $challenge->category->icon ?? 'fas fa-code' }} mr-1"></i> 
                                            {{ $challenge->category->name ?? 'Unknown' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-500 text-white">Unknown Category</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $challenge->created_at ? $challenge->created_at->diffForHumans() : 'Unknown' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(auth()->user() && auth()->user()->student && $challenge->challenger_id == auth()->user()->student->id)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary text-white">Your Turn</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-500 text-white">Opponent's Turn</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('student.challenges.show', $challenge->id) }}" 
                                       class="bg-primary hover:bg-red-800 text-white px-3 py-1 rounded text-sm transition-colors">
                                        Continue
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-blue-800">No active challenges at the moment.</p>
            </div>
        @endif
    </div>

    <!-- Recent Completed Challenges -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Recent Completed Challenges</h3>
                <p class="text-gray-600">Your recently completed challenge matches</p>
            </div>
            <a href="{{ route('student.challenges.history') }}" class="bg-white border border-primary text-primary hover:bg-primary hover:text-white px-3 py-1 rounded text-sm transition-colors">
                View All History
            </a>
        </div>

        @if($completedChallenges->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Challenger</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opponent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Result</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($completedChallenges as $challenge)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ($challenge->challenger && $challenge->challenger->full_name) ? $challenge->challenger->full_name : 'Unknown' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ($challenge->opponent && $challenge->opponent->full_name) ? $challenge->opponent->full_name : 'Random Opponent' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($challenge->category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white" style="background-color: {{ $challenge->category->color ?? '#950713' }}">
                                            <i class="{{ $challenge->category->icon ?? 'fas fa-code' }} mr-1"></i> 
                                            {{ $challenge->category->name ?? 'Unknown' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-500 text-white">Unknown Category</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $challenge->completed_at ? $challenge->completed_at->diffForHumans() : 'Unknown' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $currentStudent = auth()->user() && auth()->user()->student ? auth()->user()->student : null;
                                        $isWinner = ($challenge->result && $currentStudent && $challenge->result->winner_id == $currentStudent->id);
                                        $isDraw = ($challenge->result && $challenge->result->is_draw);
                                    @endphp
                                    
                                    @if($isDraw)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-500 text-white">Draw</span>
                                    @elseif($isWinner)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500 text-white">Won</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500 text-white">Lost</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('student.challenges.result', $challenge->id) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                        View Result
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-blue-800">No completed challenges yet.</p>
            </div>
        @endif
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
