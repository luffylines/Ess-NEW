@extends('layouts.welcome')

@section('content')
<div class="container mt-5 pt-4">

    <!-- Hero Section -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h1 class="display-5 fw-bold">Task Management</h1>
            <p class="lead mt-3">
                Empower employees to plan, organize, and track their work efficiently with our intuitive task management system. Stay on top of daily goals, upcoming deadlines, and project milestones – all in one place.
            </p>
        </div>
        <div class="col-md-6 text-center">
            <img src="https://imgs.search.brave.com/8qmPd2Sf-VhK07-Kl0FNCiiC25kypyQwF5Y4fPRSH-Y/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jZG5p/Lmljb25zY291dC5j/b20vaWxsdXN0cmF0/aW9uL3ByZW1pdW0v/dGh1bWIvdGFzay1t/YW5hZ2VtZW50LWls/bHVzdHJhdGlvbi1z/dmctcG5nLWRvd25s/b2FkLTMzMzc2MzQu/cG5n" alt="Task Management" class="img-fluid rounded shadow">
        </div>
    </div>

    <!-- Feature Cards -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="https://imgs.search.brave.com/1cfEwUIizWhPRna834yf19WxmMwhT4Mzjqfp9z2Ga64/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jZG5p/Lmljb25zY291dC5j/b20vaWxsdXN0cmF0/aW9uL3ByZW1pdW0v/dGh1bWIvdGFzay1t/YW5hZ2VtZW50LWls/bHVzdHJhdGlvbi1z/dmctcG5nLWRvd25s/b2FkLTk2NjQ0OTUu/cG5n" class="card-img-top" alt="Dashboard View">
                <div class="card-body">
                    <h5 class="card-title">Personal Task Dashboard</h5>
                    <p class="card-text">Employees can view, prioritize, and manage their tasks through a personalized dashboard tailored to their role.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="https://imgs.search.brave.com/FUUa_qH_Qow5KkwTLzUs129vsjPeT1uyyxAS8CLMDQg/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/cHJlbWl1bS12ZWN0/b3IvdGFzay1tYW5h/Z2VtZW50LWFic3Ry/YWN0LWNvbmNlcHQt/dmVjdG9yLWlsbHVz/dHJhdGlvbl8xMDcx/NzMtMjU3MDUuanBn/P3NlbXQ9YWlzX2h5/YnJpZCZ3PTc0MCZx/PTgw" class="card-img-top" alt="Task Deadlines">
                <div class="card-body">
                    <h5 class="card-title">Reminders & Deadlines</h5>
                    <p class="card-text">Never miss a deadline. Employees receive automated alerts and reminders for upcoming tasks and priorities.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="https://imgs.search.brave.com/QHFn3v-21vA88hWyHe_MS_pF6ulvXwMERnVTvBQP0CM/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/ZnJlZS12ZWN0b3Iv/dGlueS1idXNpbmVz/cy1wZXJzb25zLXdv/cmtpbmctamlnc2F3/LXB1enpsZS10b2dl/dGhlci1tZXRhcGhv/ci1jb29wZXJhdGlv/bi1wYXJ0bmVyc2hp/cC1jb2xsYWJvcmF0/aW9uLXRlYW0tcGVv/cGxlLWZsYXQtdmVj/dG9yLWlsbHVzdHJh/dGlvbi1jb21tdW5p/Y2F0aW9uLXRlYW13/b3JrLWNvbmNlcHRf/NzQ4NTUtMjUzMjgu/anBnP3NlbXQ9YWlz/X2h5YnJpZCZ3PTc0/MCZxPTgw" class="card-img-top" alt="Collaboration">
                <div class="card-body">
                    <h5 class="card-title">Seamless Collaboration</h5>
                    <p class="card-text">Assign tasks, add comments, and track progress in real-time for better collaboration between teams.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="row mt-5 py-5 bg-light rounded shadow-sm">
        <div class="col text-center">
            <h2 class="mb-3">Work Smarter, Not Harder</h2>
            <p class="mb-4">
                With ESS Task Management, productivity is just a click away. Whether you're working solo or as a team, everything you need is in one place.
            </p>
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Start Managing Tasks</a>
        </div>
    </div>

</div>
@endsection
