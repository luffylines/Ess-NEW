<aside class="main-sidebar sidebar-dark-primary elevation-4 collapsed" id="sidebar">
    <div class="brand-container d-flex justify-content-between align-items-center px-3" style="height: 56px;">
        <a href="{{ url('/') }}" class="brand-link m-0 p-0">
            <span class="brand-text font-weight-light">ESS</span>
        </a>
        <button id="sidebarToggle">
            <img src="{{ asset('img/menu.png') }}" alt="Toggle Sidebar" width="24" height="24" />
        </button>
    </div>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">
                {{-- ADMIN ONLY --}}
                @if(auth()->user()->role == 'admin')
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Employees</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.activity-logs.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Activity Logs</p>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a id="deductionsToggle" class="nav-link dropdown-toggle" href="#">
                        Deductions & Contributions
                    </a>
                    <ul class="nav nav-treeview ms-3" id="deductionsMenu" style="display: none;">
                        <li class="nav-item">
                            <a href="{{ route('admin.loans.sss') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>SSS Loan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.loans.pagibig') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pag-Ibig Loan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.loans.company') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Company Loan</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                {{-- HR ONLY --}}
                @if(auth()->user()->role == 'hr')
                <li class="nav-item"><a class="nav-link" href="{{ route('hr.approve') }}">Approve Attendance</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('hr.attendance') }}">Monitor Attendance</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('hr.approveleave.show') }}">Approve Leave</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('hr.approveOvertime.show') }}">Approve Overtime</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('hr.reports') }}">Generate Reports</a></li>
                @endif

                {{-- EMPLOYEE --}}
                @if(auth()->user()->role == 'employee')
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('profile.edit') }}">My Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('attendance.my') }}">My Attendance</a></li>
                 <li class="nav-item"><a class="nav-link" href="{{ route('overtime.index') }}">My Overtime</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('leave.index') }}">My Leave Requests</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('payslip.index') }}">Payslips</a></li>
                @endif
            </ul>
        </nav>
    </div>
</aside>

<style>
    .main-sidebar {
        width: 250px;
        background-color: #343a40;
        color: white;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        transition: all 0.3s ease;
        overflow-x: hidden;
        z-index: 1000;
        display: flex;
        flex-direction: column;
    }

    .brand-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        height: 56px;
        background-color: #23272b;
        flex-shrink: 0;
    }

    .main-sidebar.collapsed {
        width: 50px;
    }

    .main-sidebar.collapsed .brand-text {
        display: none;
    }

    .main-sidebar.collapsed .sidebar {
        display: none;
    }

    .main-sidebar.collapsed .brand-container {
        justify-content: center;
    }

    #sidebarToggle {
        cursor: pointer;
        border: none;
        background: none;
        color: white;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    let hideTimeout;

    // Function to show sidebar
    function showSidebar() {
        sidebar.classList.remove('collapsed');
        clearTimeout(hideTimeout);
    }

    // Function to hide sidebar after delay
    function hideSidebar() {
        hideTimeout = setTimeout(() => {
            sidebar.classList.add('collapsed');
        }, 300); // adjust delay as needed
    }

    // Show sidebar when hovering over toggle
    toggleBtn.addEventListener('mouseenter', showSidebar);
    // Keep sidebar open while hovering over it
    sidebar.addEventListener('mouseenter', showSidebar);

    // Hide when mouse leaves both toggle and sidebar
    toggleBtn.addEventListener('mouseleave', hideSidebar);
    sidebar.addEventListener('mouseleave', hideSidebar);

    // Deductions dropdown toggle
    const deductionsToggle = document.getElementById('deductionsToggle');
    const deductionsMenu = document.getElementById('deductionsMenu');

    deductionsToggle.addEventListener('click', function (e) {
        e.preventDefault();
        deductionsMenu.style.display = deductionsMenu.style.display === 'none' ? 'block' : 'none';
    });
</script>
