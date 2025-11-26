<aside class="main-sidebar" id="sidebar">
    <!-- Brand Logo and Toggle Button -->
    <div class="brand-container d-flex justify-content-between align-items-center px-3 py-2">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('img/logo.png') }}" alt="Company Logo" class="sidebar-logo" style="width: 28px; height: 28px; object-fit: contain;">
            <span class="brand-text fw-bold text-truncate" style="font-size: 0.95rem;">Place Of Beauty</span>
        </div>
        <button id="sidebarToggle" type="button" title="Toggle Sidebar">
            <img src="{{ asset('img/menu.png') }}" alt="Toggle Sidebar" width="24" height="24" />
        </button>
    </div>

    <!-- User Profile Section -->
    <div class="user-panel text-center mt-3 mb-3" id="userPanel">
       @if(auth()->user()->profile_photo && file_exists(public_path('storage/' . auth()->user()->profile_photo)))
            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" 
                class="img-circle profile-pic" 
                alt="User Image">
        @else
            <img src="{{ asset('img/default-profile.png') }}" 
                class="img-circle profile-pic" 
                alt="Default Profile">
        @endif
        <div class="info mt-2">
            <span class="fw-semibold d-block">{{ auth()->user()->name }}</span>
            <small class="">Employee ID: {{ auth()->user()->employee_id }}</small>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <div class="sidebar mt-2 flex-grow-1">
        <nav>
            <ul class="nav flex-column">

                {{-- ADMIN ONLY --}}
                @if(auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <i class="fas fa-tachometer-alt nav-icon me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.index') }}" class="nav-link">
                            <i class="fas fa-users nav-icon me-2"></i> Employees
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.activity-logs.index') }}" class="nav-link">
                            <i class="fas fa-history nav-icon me-2"></i> Activity Logs
                        </a>
                    </li>
                @endif

                {{-- HR AND MANAGER --}}
                @if(auth()->user()->role === 'hr' || auth()->user()->role === 'manager')
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <i class="fas fa-tachometer-alt nav-icon me-2"></i> Dashboard
                        </a>
                    </li>
                    
                    <!-- Attendance Management Dropdown -->
                    <li class="nav-item">
                        <a href="#" class="nav-link dropdown-toggle" id="attendanceToggle">
                            <i class="fas fa-calendar-check nav-icon me-2"></i> Attendance Management
                        </a>
                        <ul class="nav flex-column ms-4" id="attendanceMenu" style="display: none;">
                            <li class="nav-item">
                                <a href="{{ route('hr.pending-approvals') }}" class="nav-link">
                                    <i class="far fa-circle me-2"></i>Pending Approvals
                                    @php
                                        $pendingCount = \App\Models\Attendance::where('status', 'pending')->count();
                                    @endphp
                                    @if($pendingCount > 0)
                                        <span class="badge bg-warning text-dark ms-1">{{ $pendingCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('hr.create-for-employee.form') }}" class="nav-link">
                                    <i class="far fa-circle me-2"></i>Create Employee Attendance
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('hr.attendance') }}" class="nav-link">
                                    <i class="far fa-circle me-2"></i>Monitor Attendance
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Payroll Management -->
                    <li class="nav-item">
                        <a href="{{ route('hr.payroll.index') }}" class="nav-link">
                            <i class="fas fa-calculator nav-icon me-2"></i> Payroll Management
                        </a>
                    </li>
                    
                    <li class="nav-item"><a href="{{ route('hr.approveleave.show') }}" class="nav-link"><i class="fas fa-plane-departure me-2"></i>Approve Leave</a></li>
                    <li class="nav-item"><a href="{{ route('hr.approveOvertime.show') }}" class="nav-link"><i class="fas fa-clock me-2"></i>Approve Overtime</a></li>
                    <li class="nav-item"><a href="{{ route('hr.reports') }}" class="nav-link"><i class="fas fa-file-alt me-2"></i>Generate Reports</a></li>
                @endif

                {{-- EMPLOYEE ONLY --}}
                @if(auth()->user()->role === 'employee')
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <i class="fas fa-tachometer-alt nav-icon me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item"><a href="{{ route('profile.edit') }}" class="nav-link"><i class="fas fa-user me-2"></i>My Profile</a></li>
                    <li class="nav-item"><a href="{{ route('attendance.my') }}" class="nav-link"><i class="fas fa-calendar-check me-2"></i>My Attendance</a></li>
                    <li class="nav-item"><a href="{{ route('overtime.index') }}" class="nav-link"><i class="fas fa-business-time me-2"></i>My Overtime</a></li>
                    <li class="nav-item"><a href="{{ route('leave.index') }}" class="nav-link"><i class="fas fa-plane me-2"></i>My Leave Requests</a></li>
                    <li class="nav-item"><a href="{{ route('payslip.index') }}" class="nav-link"><i class="fas fa-file-invoice-dollar me-2"></i>Payslips</a></li>
                    
                @endif

            </ul>
        </nav>
    </div>

   <!-- Logout Button -->
    <div class="logout-container mt-auto mb-3 text-center" id="logoutSection">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm px-4">
                <i class="fas fa-sign-out-alt me-2"></i> <span class="menu-text">Logout</span>
            </button>
        </form>
    </div>
</aside>

{{-- ✅ Sidebar Styles --}}
<style>
.main-sidebar {
    width: 60px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #ffffff;
    color: #212529;
    border-right: 1px solid #dee2e6;
    transition: width 0.3s ease, all 0.3s ease;
    overflow: hidden;
    z-index: 1040;
    display: flex;
    flex-direction: column;
}

/* Expanded on hover (desktop) */
.main-sidebar.expanded {
    width: 250px;
}

/* Hide all details when collapsed */
.main-sidebar:not(.expanded) #userPanel,
.main-sidebar:not(.expanded) .menu-text,
.main-sidebar:not(.expanded) .brand-text,
.main-sidebar:not(.expanded) #logoutSection {
    display: none;
}

/* Keep icons and logos visible when collapsed */
.nav-link i,
.sidebar-logo {
    width: 40px;
    text-align: center;
}

/* Brand */
.brand-container {
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.sidebar-logo {
    transition: transform 0.3s ease;
}

.sidebar-logo:hover {
    transform: scale(1.1);
}

.brand-text {
    transition: opacity 0.3s ease;
}

.brand-logo {
    width: 28px;
    height: 28px;
}

/* Toggle button */
#sidebarToggle {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
}

/* Profile section */
.user-panel {
    padding: 10px 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.profile-pic {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #0d6efd;
}

/* Menu links */
.nav-link {
    color: inherit !important;
    padding: 10px 16px;
    display: flex;
    align-items: center;
    border-radius: 4px;
    transition: background 0.2s ease;
    font-size: 15px;
}

.nav-link:hover {
    background-color: rgba(13, 110, 253, 0.2);
}

/* Logout button */
.logout-container button {
    width: 80%;
    border-radius: 20px;
    font-size: 14px;
}

/* Dark mode support */
body.dark .main-sidebar {
    background-color: #1f1f1f;
    color: #ffffff;
    border-right: 1px solid #2c2c2c;
}

/* Responsive (mobile view) */
@media (max-width: 768px) {
    .main-sidebar {
        left: -250px;
        width: 250px;
        transition: left 0.3s ease;
    }

    .main-sidebar.active {
        left: 0;
    }
}

</style>

{{-- ✅ Sidebar Script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    // 🖥️ Desktop hover behavior
    function enableHoverBehavior() {
        sidebar.classList.remove('expanded');
        sidebar.addEventListener('mouseenter', () => sidebar.classList.add('expanded'));
        sidebar.addEventListener('mouseleave', () => sidebar.classList.remove('expanded'));
    }

    // 📱 Mobile toggle behavior
    function enableClickBehavior() {
        sidebar.classList.remove('expanded');
        sidebar.classList.remove('active');
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
    }

    // Check screen width to set correct behavior
    function checkViewport() {
        if (window.innerWidth > 768) {
            enableHoverBehavior();
        } else {
            enableClickBehavior();
        }
    }

    checkViewport();

    // Re-check when resizing window
    window.addEventListener('resize', () => {
        sidebar.classList.remove('expanded', 'active');
        checkViewport();
    });

    // Dropdown toggles
    const attendanceToggle = document.getElementById('attendanceToggle');
    const attendanceMenu = document.getElementById('attendanceMenu');
    const deductionsToggle = document.getElementById('deductionsToggle');
    const deductionsMenu = document.getElementById('deductionsMenu');

    if (attendanceToggle && attendanceMenu) {
        attendanceToggle.addEventListener('click', function(e) {
            e.preventDefault();
            if (attendanceMenu.style.display === 'none' || attendanceMenu.style.display === '') {
                attendanceMenu.style.display = 'block';
                attendanceToggle.classList.add('active');
            } else {
                attendanceMenu.style.display = 'none';
                attendanceToggle.classList.remove('active');
            }
        });
    }

    if (deductionsToggle && deductionsMenu) {
        deductionsToggle.addEventListener('click', function(e) {
            e.preventDefault();
            if (deductionsMenu.style.display === 'none' || deductionsMenu.style.display === '') {
                deductionsMenu.style.display = 'block';
                deductionsToggle.classList.add('active');
            } else {
                deductionsMenu.style.display = 'none';
                deductionsToggle.classList.remove('active');
            }
        });
    }
});
</script>
