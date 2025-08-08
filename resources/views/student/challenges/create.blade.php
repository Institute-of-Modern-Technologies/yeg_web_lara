@extends('layouts.student-unified')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Gamified Battle Setup Header -->
    <div class="relative mb-8 bg-gradient-to-r from-red-500 via-pink-500 to-purple-600 rounded-2xl p-8 text-white overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-black bg-opacity-10">
            <div class="absolute top-4 right-4 text-6xl opacity-20">
                <i class="fas fa-chess"></i>
            </div>
            <div class="absolute bottom-4 left-4 text-4xl opacity-20">
                <i class="fas fa-crosshairs"></i>
            </div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-8xl opacity-5">
                <i class="fas fa-sword-cross"></i>
            </div>
        </div>
        
        <div class="relative z-10 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold mb-2 flex items-center">
                    <i class="fas fa-crosshairs mr-3 text-yellow-300"></i>
                    Setup Battle
                </h1>
                <p class="text-pink-100 text-lg">⚔️ Choose your opponent • 🎯 Select difficulty • 💪 Prove your skills</p>
                <div class="flex items-center mt-3 space-x-4">
                    <div class="flex items-center bg-white bg-opacity-20 rounded-full px-3 py-1">
                        <i class="fas fa-bolt text-yellow-300 mr-2"></i>
                        <span class="font-semibold">Quick Match</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-20 rounded-full px-3 py-1">
                        <i class="fas fa-users text-blue-300 mr-2"></i>
                        <span class="font-semibold">Challenge Friend</span>
                    </div>
                </div>
            </div>
            <div>
                <a href="{{ route('student.challenges.index') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-6 py-3 rounded-xl font-bold text-lg shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Arena
                </a>
            </div>
        </div>
    </div>

    <!-- Battle Configuration Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Battle Setup Form -->
        <div class="lg:col-span-2">
            <div class="bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-xl p-8 border border-blue-200">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-full p-3 mr-4">
                        <i class="fas fa-cog text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">⚙️ Battle Configuration</h3>
                        <p class="text-gray-600">Configure your epic coding duel settings</p>
                    </div>
                </div>

                <form action="{{ route('student.challenges.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <ul class="text-red-800 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Battle Arena Selection -->
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-200">
                        <div class="flex items-center mb-4">
                            <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-full p-2 mr-3">
                                <i class="fas fa-map text-white"></i>
                            </div>
                            <label for="category_id" class="text-lg font-bold text-gray-800">
                                🎯 Choose Battle Arena <span class="text-red-500">*</span>
                            </label>
                        </div>
                        <select class="w-full px-4 py-3 border-2 border-purple-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 @error('category_id') border-red-500 @enderror bg-white text-gray-800 font-medium" 
                                id="category_id" 
                                name="category_id" 
                                required>
                            <option value="">🎮 Select your battlefield...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        data-color="{{ $category->color }}"
                                        data-icon="{{ $category->icon }}"
                                        {{ old('category_id') == $category->id || request('category') == $category->id ? 'selected' : '' }}>
                                    ⚔️ {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="mt-2 flex items-center text-red-600">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <p class="text-sm font-medium">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <!-- Battle Mode Selection -->
                    <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-xl p-6 border border-green-200">
                        <div class="flex items-center mb-4">
                            <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-full p-2 mr-3">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <label class="text-lg font-bold text-gray-800">
                                🎯 Battle Mode <span class="text-red-500">*</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="relative">
                                <input type="radio" id="specific_opponent" name="opponent_type" value="specific" class="peer sr-only" checked>
                                <label for="specific_opponent" class="flex items-center p-4 bg-white border-2 border-green-300 rounded-xl cursor-pointer hover:bg-green-50 peer-checked:border-green-500 peer-checked:bg-green-100 transition-all duration-200">
                                    <div class="flex items-center">
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-full p-2 mr-3">
                                            <i class="fas fa-crosshairs text-white"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">🎯 Target Duel</div>
                                            <div class="text-sm text-gray-600">Challenge a specific warrior</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="relative">
                                <input type="radio" id="random_opponent" name="opponent_type" value="random" class="peer sr-only">
                                <label for="random_opponent" class="flex items-center p-4 bg-white border-2 border-green-300 rounded-xl cursor-pointer hover:bg-green-50 peer-checked:border-green-500 peer-checked:bg-green-100 transition-all duration-200">
                                    <div class="flex items-center">
                                        <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-full p-2 mr-3">
                                            <i class="fas fa-dice text-white"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">🎲 Random Battle</div>
                                            <div class="text-sm text-gray-600">Fight any available opponent</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Target Opponent Selection -->
                    <div id="opponent_selector" class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-6 border border-orange-200">
                        <div class="flex items-center mb-4">
                            <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-full p-2 mr-3">
                                <i class="fas fa-user-ninja text-white"></i>
                            </div>
                            <label for="opponent_id" class="text-lg font-bold text-gray-800">
                                🎯 Choose Your Opponent <span class="text-red-500">*</span>
                            </label>
                        </div>
                        <!-- Search Input -->
                        <div class="relative mb-4">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-orange-400"></i>
                            </div>
                            <input type="text" 
                                   id="opponent_search" 
                                   placeholder="🔍 Search for your rival warrior..." 
                                   class="w-full pl-12 pr-4 py-3 border-2 border-orange-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-orange-500 bg-white text-gray-800 font-medium">
                        </div>
                        
                        <!-- Hidden Select for Form Submission -->
                        <select class="hidden" id="opponent_id" name="opponent_id">
                            <option value=""></option>
                            @foreach($opponents as $opponent)
                                <option value="{{ $opponent->id }}" {{ old('opponent_id') == $opponent->id ? 'selected' : '' }}>
                                    {{ $opponent->full_name }}
                                </option>
                            @endforeach
                        </select>
                        
                        <!-- Searchable Opponent List -->
                        <div id="opponent_list" class="max-h-60 overflow-y-auto border-2 border-orange-300 rounded-xl bg-white">
                            @foreach($opponents as $opponent)
                                <div class="opponent-item p-4 hover:bg-orange-50 cursor-pointer border-b border-orange-100 last:border-b-0 transition-colors duration-200" 
                                     data-id="{{ $opponent->id }}" 
                                     data-name="{{ strtolower($opponent->full_name) }}">
                                    <div class="flex items-center">
                                        <div class="bg-gradient-to-r from-orange-400 to-red-400 rounded-full w-10 h-10 flex items-center justify-center mr-3">
                                            <i class="fas fa-user-ninja text-white"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">⚔️ {{ $opponent->full_name }}</div>
                                            <div class="text-sm text-gray-600">🎯 Ready for battle</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            <!-- No Results Message -->
                            <div id="no_results" class="hidden p-6 text-center text-gray-500">
                                <i class="fas fa-search text-4xl mb-3 opacity-50"></i>
                                <p class="font-medium">🤷‍♂️ No warriors found</p>
                                <p class="text-sm">Try a different search term</p>
                            </div>
                        </div>
                        
                        <!-- Selected Opponent Display -->
                        <div id="selected_opponent" class="hidden mt-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-300 rounded-xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-full w-10 h-10 flex items-center justify-center mr-3">
                                        <i class="fas fa-check text-white"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800">🎯 Selected Opponent:</div>
                                        <div id="selected_name" class="text-green-700 font-semibold"></div>
                                    </div>
                                </div>
                                <button type="button" id="clear_selection" class="text-red-500 hover:text-red-700 transition-colors">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                        </div>
                        @error('opponent_id')
                            <div class="mt-2 flex items-center text-red-600">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <p class="text-sm font-medium">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <!-- Battle Intensity Settings -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Question Count -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                            <div class="flex items-center mb-4">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full p-2 mr-3">
                                    <i class="fas fa-list-ol text-white"></i>
                                </div>
                                <label for="question_count" class="text-lg font-bold text-gray-800">
                                    📊 Battle Length <span class="text-red-500">*</span>
                                </label>
                            </div>
                            <select class="w-full px-4 py-3 border-2 border-blue-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 @error('question_count') border-red-500 @enderror bg-white text-gray-800 font-medium" 
                                    id="question_count" 
                                    name="question_count">
                                <option value="5" {{ old('question_count') == 5 ? 'selected' : '' }}>⚡ Quick Skirmish (5 Questions)</option>
                                <option value="10" {{ old('question_count', 10) == 10 ? 'selected' : '' }}>⚔️ Standard Battle (10 Questions)</option>
                                <option value="15" {{ old('question_count') == 15 ? 'selected' : '' }}>🔥 Epic War (15 Questions)</option>
                            </select>
                            @error('question_count')
                                <div class="mt-2 flex items-center text-red-600">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <p class="text-sm font-medium">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        <!-- Difficulty Level -->
                        <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-6 border border-red-200">
                            <div class="flex items-center mb-4">
                                <div class="bg-gradient-to-r from-red-500 to-pink-500 rounded-full p-2 mr-3">
                                    <i class="fas fa-chart-line text-white"></i>
                                </div>
                                <label for="difficulty" class="text-lg font-bold text-gray-800">
                                    🎯 Difficulty Level
                                </label>
                            </div>
                            <select class="w-full px-4 py-3 border-2 border-red-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-red-500 @error('difficulty') border-red-500 @enderror bg-white text-gray-800 font-medium" 
                                    id="difficulty" 
                                    name="difficulty">
                                <option value="mixed" {{ old('difficulty', 'mixed') == 'mixed' ? 'selected' : '' }}>🌈 Mixed Challenge (Easy, Medium, Hard)</option>
                                <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>😊 Beginner Friendly (Easy Only)</option>
                                <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>💪 Intermediate Level (Medium Only)</option>
                                <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>🔥 Expert Mode (Hard Only)</option>
                            </select>
                            @error('difficulty')
                                <div class="mt-2 flex items-center text-red-600">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <p class="text-sm font-medium">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Epic Submit Button -->
                    <div class="text-center pt-4">
                        <button type="submit" class="bg-gradient-to-r from-red-500 via-pink-500 to-purple-600 hover:from-red-600 hover:via-pink-600 hover:to-purple-700 text-white px-12 py-4 rounded-2xl font-bold text-xl shadow-2xl transform hover:scale-105 transition-all duration-300 inline-flex items-center">
                            <i class="fas fa-sword-cross mr-3 text-2xl"></i>
                            ⚔️ INITIATE BATTLE!
                            <i class="fas fa-fire ml-3 text-2xl animate-pulse"></i>
                        </button>
                        <p class="text-gray-600 text-sm mt-3">🏆 May the best coder win!</p>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">How It Works</h3>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="flex items-center text-blue-800 font-medium mb-3">
                        <i class="mdi mdi-information-outline mr-2"></i> Challenge Rules
                    </h4>
                    <ol class="text-blue-800 space-y-2 text-sm">
                        <li>1. Select a category and opponent</li>
                        <li>2. Both players answer the same questions</li>
                        <li>3. Questions have time limits</li>
                        <li>4. Faster correct answers earn more points</li>
                        <li>5. The player with the most points wins!</li>
                    </ol>
                </div>
                
                <div id="category_info" class="mt-6 hidden">
                    <h4 class="text-gray-900 font-medium mb-3">Selected Category</h4>
                    <div class="flex items-center">
                        <span id="category_icon" class="mr-3 w-10 h-10 flex items-center justify-center rounded-full"></span>
                        <div>
                            <h4 id="category_name" class="font-semibold text-gray-900"></h4>
                            <p id="category_desc" class="text-gray-600 text-sm"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle opponent type selection
        $('input[name="opponent_type"]').change(function() {
            if ($(this).val() === 'specific') {
                $('#opponent_selector').show();
                $('#opponent_id').prop('required', true);
            } else {
                $('#opponent_selector').hide();
                $('#opponent_id').prop('required', false);
                // Clear selection when switching to random
                clearOpponentSelection();
            }
        });
        
        // Handle category selection and display category info
        $('#category_id').change(function() {
            const selectedOption = $(this).find('option:selected');
            const categoryName = selectedOption.text();
            const categoryColor = selectedOption.data('color');
            const categoryIcon = selectedOption.data('icon');
            
            if ($(this).val()) {
                $('#category_info').removeClass('d-none');
                $('#category_name').text(categoryName);
                $('#category_icon').html(`<i class="${categoryIcon} fa-lg"></i>`);
                $('#category_icon').css('background-color', categoryColor);
                $('#category_icon i').css('color', '#fff');
            } else {
                $('#category_info').addClass('d-none');
            }
        });
        
        // Searchable Opponent Selection Functionality
        const opponentSearch = $('#opponent_search');
        const opponentList = $('#opponent_list');
        const selectedOpponent = $('#selected_opponent');
        const selectedName = $('#selected_name');
        const clearSelection = $('#clear_selection');
        const opponentIdSelect = $('#opponent_id');
        const noResults = $('#no_results');
        
        // Search functionality
        opponentSearch.on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            const opponentItems = $('.opponent-item');
            let visibleCount = 0;
            
            opponentItems.each(function() {
                const opponentName = $(this).data('name');
                if (opponentName.includes(searchTerm)) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide();
                }
            });
            
            // Show/hide no results message
            if (visibleCount === 0 && searchTerm.length > 0) {
                noResults.show();
            } else {
                noResults.hide();
            }
        });
        
        // Opponent selection
        $('.opponent-item').on('click', function() {
            const opponentId = $(this).data('id');
            const opponentName = $(this).find('.font-bold').text().replace('⚔️ ', '');
            
            // Update hidden select
            opponentIdSelect.val(opponentId);
            
            // Show selected opponent
            selectedName.text(opponentName);
            selectedOpponent.show();
            
            // Hide opponent list and clear search
            opponentList.hide();
            opponentSearch.val('');
            
            // Add visual feedback
            $(this).addClass('bg-green-100 border-green-300');
            setTimeout(() => {
                $(this).removeClass('bg-green-100 border-green-300');
            }, 300);
        });
        
        // Clear selection
        clearSelection.on('click', function() {
            clearOpponentSelection();
        });
        
        // Function to clear opponent selection
        function clearOpponentSelection() {
            opponentIdSelect.val('');
            selectedOpponent.hide();
            opponentList.show();
            opponentSearch.val('');
            $('.opponent-item').show();
            noResults.hide();
        }
        
        // Show/hide opponent list when clicking search input
        opponentSearch.on('focus', function() {
            if (!selectedOpponent.is(':visible')) {
                opponentList.show();
            }
        });
        
        // Hide opponent list when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#opponent_selector').length) {
                if (opponentIdSelect.val()) {
                    opponentList.hide();
                }
            }
        });
        
        // Trigger change event if category is pre-selected
        if ($('#category_id').val()) {
            $('#category_id').trigger('change');
        }
        
        // Initialize - hide opponent selector if random is selected
        if ($('input[name="opponent_type"]:checked').val() === 'random') {
            $('#opponent_selector').hide();
        }
    });
</script>
@endsection
