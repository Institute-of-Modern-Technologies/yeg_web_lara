@extends('admin.dashboard')

@section('content')
<div class="p-6">
    <div class="flex items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Processing Import</h1>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="flex justify-center items-center mb-4">
                <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12"></div>
            </div>
            <p class="text-center text-gray-700">Please wait while we process your import...</p>

            <form id="processForm" action="{{ $route }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </div>
</div>

<style>
.loader {
    border-top-color: #4f46e5;
    -webkit-animation: spinner 1s linear infinite;
    animation: spinner 1s linear infinite;
}

@-webkit-keyframes spinner {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
}

@keyframes spinner {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
    // Submit the form automatically when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            // Ensure we're using a POST request
            const form = document.getElementById('processForm');
            form.method = 'POST'; // Force the method to be POST
            
            // Add a hidden input for _method if needed
            if (!form.querySelector('input[name="_token"]')) {
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(tokenInput);
            }
            
            form.submit();
        }, 1000); // Wait 1 second to show the loading spinner
    });
</script>
@endsection
