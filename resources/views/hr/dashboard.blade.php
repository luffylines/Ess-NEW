<x-app-layout>
<div class="container">
    <h1>HR Dashboard</h1>
    <p>Welcome, {{ Auth::user()->name }}! You have HR access.</p>
    {{-- Add HR-specific dashboard content here --}}
</div>
</x-app-layout>