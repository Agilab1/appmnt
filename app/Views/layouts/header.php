<?php
$role = session()->get('role');

if ($role === 'admin') {
    $name = session()->get('admin_name');
} elseif ($role === 'staff') {
    $name = session()->get('staff_name');
} else {
    $name = 'User';
}

$initials = '';
if (!empty($name)) {
    $parts = explode(' ', trim($name));
    $initials = strtoupper(
        substr($parts[0], 0, 1) .
            (isset($parts[1]) ? substr($parts[1], 0, 1) : '')
    );
}
?>
<style>
    .header-wrapper {
        width: 100%;
    }

    /* MOBILE */
    @media (max-width: 768px) {
        .header-wrapper {
            margin-left: 0;
            padding: 10px;
        }
    }


    .app-header {
        height: 60px;
        background: #ffffff;
        border-radius: 14px;
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    /* ================= LEFT SIDE ================= */

    .header-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* MENU ICON */
    .header-left a i {
        cursor: pointer;
    }

    /* ================= SEARCH BOX ================= */

    .search-box {
        background: #f1f5f9;
        border-radius: 25px;
        padding: 6px 14px;
        display: flex;
        align-items: center;
        width: 260px;
        transition: 0.2s;
    }

    .search-box:hover {
        background: #e2e8f0;
    }

    .search-box input {
        border: none;
        outline: none;
        background: transparent;
        margin-left: 8px;
        font-size: 13px;
        width: 100%;
    }

    /* ================= RIGHT SIDE ================= */

    .header-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    /* ================= ICONS ================= */

    .header-icon {
        font-size: 18px;
        color: #6b7280;
        position: relative;
        cursor: pointer;
        transition: 0.2s;
    }

    .header-icon:hover {
        color: #111827;
    }

    /* ================= BADGE ================= */

    .header-badge {
        position: absolute;
        top: -5px;
        right: -6px;
        font-size: 10px;
        padding: 2px 5px;
        border-radius: 50%;
    }

    /* ================= USER ================= */

    .user-box {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    /* AVATAR */
    .avatar {
        width: 34px;
        height: 34px;
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
        border-radius: 50%;
        color: #fff;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    /* USERNAME */
    .username {
        font-size: 14px;
        font-weight: 500;
        color: #111827;
    }

    /* ================= DROPDOWN ================= */

    .dropdown-menu {
        border-radius: 12px;
        padding: 10px 0;
    }

    .dropdown-menu .btn {
        border-radius: 6px;
    }

    /* ================= EXTRA FIX ================= */

    /* prevent overflow */
    .app-header,
    .header-wrapper {
        max-width: 100%;
    }

    /* smooth transition */
    * {
        transition: all 0.2s ease-in-out;
    }
</style>
<!-- 🔥 WRAPPER -->
<div class="header-wrapper">

    <nav class="app-header">

        <!-- LEFT -->
        <div class="header-left">

            <a href="#" data-lte-toggle="sidebar">
                <i class="bi bi-list fs-4 text-dark"></i>
            </a>

            <div class="search-box d-none d-md-flex">
                <i class="bi bi-search text-secondary"></i>
                <input type="text" placeholder="Search anything...">
            </div>

        </div>

        <!-- RIGHT -->
        <div class="header-right">

            <i class="bi bi-search header-icon d-md-none"></i>

            <div class="position-relative">
                <i class="bi bi-chat-dots header-icon"></i>
                <span class="badge bg-danger header-badge">3</span>
            </div>

            <div class="position-relative">
                <i class="bi bi-bell header-icon"></i>
                <span class="badge bg-warning text-dark header-badge">15</span>
            </div>

            <a href="#" data-lte-toggle="fullscreen">
                <i class="bi bi-arrows-fullscreen header-icon"></i>
            </a>

            <!-- USER -->
            <div class="dropdown">
                <a href="#" class="user-box" data-bs-toggle="dropdown">
                    <!-- <span class="username d-none d-md-inline"><?= esc($name) ?></span> -->
                    <div class="avatar"><?= esc($initials) ?></div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li class="text-center p-3">
                        <div class="avatar mx-auto mb-2" style="width:50px;height:50px;">
                            <?= esc($initials) ?>
                        </div>
                        <b><?= esc($name) ?></b><br>
                        <small><?= ucfirst($role) ?></small>
                    </li>
                    <li>
                        <hr>
                    </li>
                    <li class="d-flex justify-content-between px-3 pb-2">
                        <a href="<?= base_url('staff/profile') ?>" class="btn btn-sm btn-outline-primary">Profile</a>
                        <a href="<?= base_url('logout') ?>" class="btn btn-sm btn-danger">Logout</a>
                    </li>
                </ul>
            </div>

        </div>

    </nav>

</div>