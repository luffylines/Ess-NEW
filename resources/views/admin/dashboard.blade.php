<x-app-layout>
<div class="container">
    <h1>Admin Dashboard</h1>
    <p>Welcome, {{ Auth::user()->name }}! You have admin access.</p>
    {{-- Add admin-specific dashboard content here --}}
</div>
</x-app-layout>