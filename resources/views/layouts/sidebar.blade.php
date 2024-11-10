<style>
    /* Sidebar styling */
    .sidebar {
        width: 250px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background-color: rgb(31 41 55);
        color: #fff;
        padding-top: 30px;
        padding-left: 20px;
        padding-right: 20px;
        box-shadow: 2px 0px 10px rgba(0, 0, 0, 0.1);
    }

    /* Sidebar Heading */
    .sidebar h4 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #fff;
    }

    /* Navigation links */
    .sidebar .nav-link {
        font-size: 1rem;
        color: #ccc;
        margin-bottom: 15px;
        transition: color 0.3s ease, padding-left 0.3s ease;
        padding-left: 0px;
    }

    /* Hover effect for links */
    .sidebar .nav-link:hover {
        color: #fff;
        padding-left: 10px;
        background-color: rgb(3 16 34);
        border-radius: 5px;
    }

    /* Active link styling */
    .sidebar .nav-link.active {
        color: #fff;
        background-color: #007bff;
        border-radius: 5px;
    }

    /* Customize the form inside the sidebar (logout form) */
    .sidebar form {
        margin-top: 20px;
    }

    /* Create smooth hover effect for sidebar links */
    .sidebar .nav-link.sidebar-link {
        position: relative;
    }

    .sidebar .nav-link.sidebar-link::before {
        content: '';
        position: absolute;
        left: 0;
        width: 4px;
        height: 100%;
        background-color: transparent;
        transition: background-color 0.3s ease;
    }

    /* Change color on hover */
    .sidebar .nav-link.sidebar-link:hover::before {
        background-color: #007bff;
    }

    /* Section for Profile and Logout */
    .sidebar .user-section {
        margin-top: 30px;
        border-top: 1px solid #444;
        padding-top: 20px;
    }

    /* Extra styling for logout section */
    .sidebar .user-section a {
        font-size: 1rem;
    }
</style>

<div class="sidebar">
    <h4 class="text-center mb-4">AI HQ Corp</h4>
    <nav class="nav flex-column">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('employee.attendance') }}" class="nav-link {{ request()->routeIs('employee.attendance') ? 'active' : '' }}">Employee Attendance</a>
    </nav>

    <!-- Separate Profile and Logout Section -->
    <div class="user-section">
        <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">Profile</a>
        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>
