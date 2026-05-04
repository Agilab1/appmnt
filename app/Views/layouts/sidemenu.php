<ul class="nav nav-pills nav-sidebar flex-column" data-lte-toggle="treeview" role="menu">

    <!-- USERS MAIN -->
    <li class="nav-item menu-open">

        <a href="#" class="nav-link active">
            <i class="nav-icon fas fa-users"></i>
            <p>
                Users
                <i class="right fas fa-angle-down"></i>
            </p>
        </a>

        <ul class="nav nav-treeview">

            <li class="nav-item">
                <a href="<?= base_url('staff/dashboard') ?>" class="nav-link active">
                    <i class="nav-icon fas fa-home"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= base_url('users') ?>" class="nav-link">
                    <i class="nav-icon fas fa-user"></i>
                    <p>Users</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-calendar"></i>
                    <p>Calendar</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-cog"></i>
                    <p>Settings</p>
                </a>
            </li>

        </ul>

    </li>

    <!-- RECENT SECTION -->
    <li class="nav-header text-uppercase mt-3" style="font-size:11px;">Recent Appointments</li>

    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-file"></i>
            <p>File Component</p>
        </a>
    </li>

</ul>