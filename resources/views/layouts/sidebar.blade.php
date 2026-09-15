<aside class="main-sidebar pob-sidebar" id="sidebar">
    <div class="brand-container">
        <a href="{{ route('dashboard') }}" class="sidebar-brand-link">
            <img src="{{ asset('img/logo.png') }}" alt="Place Of Beauty" class="sidebar-logo">
            <div class="brand-copy menu-text"><strong>Place Of Beauty</strong><small>Employee Self-Service</small></div>
        </a>
        <button id="sidebarToggle" type="button" class="sidebar-toggle" aria-label="Toggle sidebar"><i class="bi bi-layout-sidebar-inset"></i></button>
    </div>

    <div class="user-panel" id="userPanel">
        @if(auth()->user()->profile_photo_url)
            <img src="{{ auth()->user()->profile_photo_url }}" class="profile-pic" alt="{{ auth()->user()->name }}">
        @else
            <img src="{{ asset('img/default-avatar.png') }}" class="profile-pic" alt="Default profile">
        @endif
        <div class="menu-text user-copy">
            <strong>{{ auth()->user()->name }}</strong>
            <span>{{ ucfirst(auth()->user()->role) }} @if(auth()->user()->employee_id) · {{ auth()->user()->employee_id }} @endif</span>
        </div>
        <span class="menu-text online-dot" title="Signed in"></span>
    </div>

    <div class="sidebar flex-grow-1">
        <nav>
            <ul class="nav flex-column pob-nav">
                <li class="menu-text nav-section-label">Workspace</li>
                <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2-fill nav-icon"></i><span class="menu-text">Dashboard</span></a></li>

                @if(auth()->user()->role === 'admin')
                    <li class="menu-text nav-section-label">Administration</li>
                    <li class="nav-item"><a href="{{ route('admin.index') }}" class="nav-link {{ request()->routeIs('admin.index') || request()->routeIs('admin.employees.*') ? 'active' : '' }}"><i class="bi bi-people-fill nav-icon"></i><span class="menu-text">Employees</span></a></li>
                    <li class="nav-item"><a href="{{ route('admin.activity-logs.index') }}" class="nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}"><i class="bi bi-activity nav-icon"></i><span class="menu-text">Activity Logs</span></a></li>
                    <li class="nav-item"><a href="{{ route('admin.stores.index') }}" class="nav-link {{ request()->routeIs('admin.stores.*') ? 'active' : '' }}"><i class="bi bi-geo-alt-fill nav-icon"></i><span class="menu-text">Store Locations</span></a></li>
                    <li class="nav-item"><a href="{{ route('admin.networks.index') }}" class="nav-link {{ request()->routeIs('admin.networks.*') ? 'active' : '' }}"><i class="bi bi-hdd-network-fill nav-icon"></i><span class="menu-text">Allowed Networks</span></a></li>
                    <li class="nav-item"><a href="{{ route('admin.sms.index') }}" class="nav-link {{ request()->routeIs('admin.sms.*') ? 'active' : '' }}"><i class="bi bi-chat-dots-fill nav-icon"></i><span class="menu-text">SMS Configuration</span></a></li>
                @endif

                @if(in_array(auth()->user()->role, ['hr','manager']))
                    <li class="menu-text nav-section-label">People Operations</li>
                    <li class="nav-item">
                        <button class="nav-link w-100 border-0 text-start sidebar-submenu-toggle" type="button" data-submenu-target="attendanceMenu">
                            <i class="bi bi-calendar2-check-fill nav-icon"></i><span class="menu-text flex-grow-1">Attendance</span><i class="bi bi-chevron-down menu-text submenu-chevron"></i>
                        </button>
                        <ul class="nav flex-column sidebar-submenu menu-text" id="attendanceMenu">
                            <li><a href="{{ route('hr.pending-approvals') }}" class="nav-link {{ request()->routeIs('hr.pending-approvals') ? 'active' : '' }}">Pending Approvals @php $pendingCount = \App\Models\Attendance::where('status','pending')->count(); @endphp @if($pendingCount > 0)<span class="badge rounded-pill ms-auto" style="background:var(--pob-champagne);color:#3e2c16;">{{ $pendingCount }}</span>@endif</a></li>
                            <li><a href="{{ route('hr.create-for-employee.form') }}" class="nav-link">Create Attendance</a></li>
                            <li><a href="{{ route('hr.attendance') }}" class="nav-link">Monitor Attendance</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a href="{{ route('schedules.index') }}" class="nav-link {{ request()->routeIs('schedules.*') ? 'active' : '' }}"><i class="bi bi-calendar-week-fill nav-icon"></i><span class="menu-text">Manage Schedules</span></a></li>
                    <li class="nav-item"><a href="{{ route('hr.payroll.index') }}" class="nav-link {{ request()->routeIs('hr.payroll.*') ? 'active' : '' }}"><i class="bi bi-wallet2 nav-icon"></i><span class="menu-text">Payroll Management</span></a></li>
                    <li class="nav-item"><a href="{{ route('hr.approveleave.show') }}" class="nav-link"><i class="bi bi-calendar2-heart-fill nav-icon"></i><span class="menu-text">Approve Leave</span></a></li>
                    <li class="nav-item"><a href="{{ route('hr.approveOvertime.show') }}" class="nav-link"><i class="bi bi-clock-history nav-icon"></i><span class="menu-text">Approve Overtime</span></a></li>
                    <li class="nav-item"><a href="{{ route('hr.reports') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph-fill nav-icon"></i><span class="menu-text">Reports</span></a></li>
                @endif

                @if(auth()->user()->role === 'employee')
                    <li class="menu-text nav-section-label">My Workday</li>
                    <li class="nav-item"><a href="{{ route('attendance.my') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}"><i class="bi bi-fingerprint nav-icon"></i><span class="menu-text">My Attendance</span></a></li>
                    <li class="nav-item"><a href="{{ route('schedules.my') }}" class="nav-link {{ request()->routeIs('schedules.my') ? 'active' : '' }}"><i class="bi bi-calendar-event-fill nav-icon"></i><span class="menu-text">My Schedule</span></a></li>
                    <li class="nav-item"><a href="{{ route('leave.index') }}" class="nav-link {{ request()->routeIs('leave.*') ? 'active' : '' }}"><i class="bi bi-calendar2-heart nav-icon"></i><span class="menu-text">Leave Requests</span></a></li>
                    <li class="nav-item"><a href="{{ route('overtime.index') }}" class="nav-link {{ request()->routeIs('overtime.*') ? 'active' : '' }}"><i class="bi bi-clock-fill nav-icon"></i><span class="menu-text">Overtime</span></a></li>
                    <li class="nav-item"><a href="{{ route('payslip.index') }}" class="nav-link {{ request()->routeIs('payslip.*') || request()->routeIs('payslips.*') ? 'active' : '' }}"><i class="bi bi-receipt-cutoff nav-icon"></i><span class="menu-text">Payslips</span></a></li>
                    <li class="nav-item"><a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"><i class="bi bi-person-circle nav-icon"></i><span class="menu-text">My Profile</span></a></li>
                @endif
            </ul>
        </nav>
    </div>

    <div class="sidebar-footer menu-text" id="logoutSection">
        <form action="{{ route('logout') }}" method="POST">@csrf
            <button type="submit" class="sidebar-logout"><i class="bi bi-box-arrow-right"></i><span>Sign out</span></button>
        </form>
    </div>
</aside>

<style>
.pob-sidebar{width:72px;height:100vh;position:fixed;inset:0 auto 0 0;z-index:1045;display:flex;flex-direction:column;background:rgba(255,255,255,.88)!important;border-right:1px solid var(--pob-line)!important;backdrop-filter:blur(20px) saturate(150%);box-shadow:8px 0 30px rgba(45,34,42,.04);overflow:hidden;transition:width .3s cubic-bezier(.2,.7,.2,1),left .3s ease}.pob-sidebar.expanded{width:270px}.pob-sidebar:not(.expanded) .menu-text{display:none!important}.brand-container{height:72px;padding:12px 14px;border-bottom:1px solid var(--pob-line);display:flex;align-items:center;justify-content:space-between}.sidebar-brand-link{display:flex;align-items:center;gap:10px;text-decoration:none;color:var(--pob-text)!important;min-width:0}.sidebar-logo{width:42px;height:42px;object-fit:contain;padding:5px;border-radius:15px;background:#fff;box-shadow:0 7px 20px rgba(60,40,50,.08);flex:none}.brand-copy{line-height:1.1;min-width:145px}.brand-copy strong{display:block;font-size:.9rem}.brand-copy small{display:block;color:var(--pob-muted);font-size:.68rem;margin-top:4px}.sidebar-toggle{width:36px;height:36px;border:1px solid var(--pob-line);background:transparent;color:var(--pob-text);border-radius:12px;display:grid;place-items:center}.user-panel{margin:14px 10px;padding:10px;display:flex;align-items:center;gap:10px;border:1px solid var(--pob-line);border-radius:18px;background:rgba(198,79,122,.045);position:relative}.profile-pic{width:40px;height:40px;border-radius:14px;object-fit:cover;border:2px solid rgba(198,79,122,.2);flex:none}.user-copy{min-width:0;line-height:1.15}.user-copy strong{display:block;font-size:.82rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.user-copy span{display:block;font-size:.68rem;color:var(--pob-muted);margin-top:4px}.online-dot{width:8px;height:8px;border-radius:50%;background:#43a77f;margin-left:auto;box-shadow:0 0 0 4px rgba(67,167,127,.12)}.sidebar{overflow-y:auto;padding:0 8px 16px}.pob-nav{gap:3px}.nav-section-label{padding:14px 10px 5px;color:var(--pob-muted);font-size:.64rem;font-weight:800;letter-spacing:.11em;text-transform:uppercase}.pob-sidebar .nav-link{height:auto;min-height:46px;padding:10px 12px;border-radius:14px;color:var(--pob-muted)!important;display:flex;align-items:center;gap:10px;font-size:.82rem;font-weight:700;transition:background .2s ease,color .2s ease,transform .2s ease}.pob-sidebar .nav-link:hover{background:rgba(198,79,122,.08);color:var(--pob-text)!important;transform:translateX(2px)}.pob-sidebar .nav-link.active{background:linear-gradient(135deg,rgba(198,79,122,.16),rgba(127,103,179,.11));color:var(--pob-rose-deep)!important}.nav-icon{width:28px;min-width:28px;text-align:center;font-size:1rem}.sidebar-submenu{margin:3px 0 6px 22px;padding-left:10px;border-left:1px solid var(--pob-line);display:none}.sidebar-submenu.open{display:flex}.sidebar-submenu .nav-link{min-height:36px;padding:7px 10px;font-size:.74rem}.submenu-chevron{transition:transform .2s ease}.sidebar-submenu-toggle.open .submenu-chevron{transform:rotate(180deg)}.sidebar-footer{padding:12px;border-top:1px solid var(--pob-line)}.sidebar-logout{width:100%;border:1px solid rgba(181,72,85,.16);border-radius:14px;padding:10px 12px;background:rgba(181,72,85,.07);color:var(--pob-danger);display:flex;align-items:center;justify-content:center;gap:8px;font-weight:800}.dark .pob-sidebar{background:rgba(29,24,29,.9)!important}.pob-sidebar::-webkit-scrollbar,.sidebar::-webkit-scrollbar{width:4px}.pob-sidebar::-webkit-scrollbar-thumb,.sidebar::-webkit-scrollbar-thumb{background:rgba(198,79,122,.25);border-radius:20px}@media(max-width:992px){.pob-sidebar{left:-290px;width:280px!important;box-shadow:18px 0 50px rgba(0,0,0,.18)}.pob-sidebar.mobile-active{left:0}.pob-sidebar.mobile-active .menu-text{display:block!important}.pob-sidebar.mobile-active .nav-link{font-size:.9rem}.pob-sidebar.mobile-active .sidebar-submenu{display:none}.pob-sidebar.mobile-active .sidebar-submenu.open{display:flex}}@media(min-width:993px){.pob-sidebar:hover{width:270px}.pob-sidebar:hover .menu-text{display:block!important}}
</style>

<script>
document.addEventListener('DOMContentLoaded',function(){const sidebar=document.getElementById('sidebar');const toggle=document.getElementById('sidebarToggle');toggle?.addEventListener('click',function(){if(window.innerWidth<=992){sidebar.classList.toggle('mobile-active')}else{sidebar.classList.toggle('expanded')}});document.querySelectorAll('.sidebar-submenu-toggle').forEach(function(button){button.addEventListener('click',function(){const menu=document.getElementById(this.dataset.submenuTarget);this.classList.toggle('open');menu?.classList.toggle('open')})});document.addEventListener('click',function(event){if(window.innerWidth<=992&&sidebar.classList.contains('mobile-active')&&!sidebar.contains(event.target)&&!event.target.closest('.mobile-menu-toggle'))sidebar.classList.remove('mobile-active')});});
</script>
