<style>
  :root {

    --sidebar-bg: #1e2640;
    --sidebar-gradient: linear-gradient(180deg, #25335a 0%, #161d31 100%);
    --sidebar-active: #3b82f6;
    --sidebar-hover: rgba(255, 255, 255, 0.05);
    --sidebar-text: #94a3b8;
    --sidebar-text-bright: #ffffff;
    --sidebar-border: rgba(255, 255, 255, 0.08);
  }

  .app-sidebar {
    position: fixed !important;
    top: 0;
    left: 0;
    bottom: 0;
    width: 260px;
    background: var(--sidebar-gradient) !important;
    border-right: 1px solid var(--sidebar-border);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1030;
    display: flex;
    flex-direction: column;
  }


  /* BRAND SECTION FIX */
 .sidebar-brand {
    width: 100%;
    padding: 10px 0;
}

.sidebar-logo {
     width: 100%;
    max-width: 220px;
    height: auto;
    object-fit: contain;
    display: block;
    margin: 0 auto;     /* center align */
}

  /* REMOVE EXTRA SPACE */
  .brand-link {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 !important;
  }
  .sidebar-brand .brand-link {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}

  /* OPTIONAL: logo container spacing */
  .sidebar-brand img {
    margin: 5px 0;
  }

  .sidebar-content {
    flex-grow: 1;
    padding: 10px 15px;
    overflow-y: auto;
  }


  .app-sidebar::-webkit-scrollbar {
    width: 4px;
  }

  .app-sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
  }


  .user-profile-bottom {
    margin: 15px;
    padding: 12px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--sidebar-border);
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    color: white;
    position: relative;
    transition: background 0.2s;
  }

  .user-profile-bottom:hover {
    background: rgba(255, 255, 255, 0.06);
  }

  .user-profile-bottom img {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    object-fit: cover;
    border: 1.5px solid rgba(255, 255, 255, 0.2);
  }

  .user-info p {
    margin: 0;
    font-size: 13.5px;
    font-weight: 600;
    letter-spacing: 0.3px;
    color: var(--sidebar-text-bright);
  }

  .user-info small {
    display: block;
    color: var(--sidebar-text);
    font-size: 11px;
    opacity: 0.8;
  }

  .profile-action-icon {
    margin-left: auto;
    color: var(--sidebar-text);
    font-size: 12px;
  }

  .nav-link {
    border-radius: 8px !important;
    margin-bottom: 4px;
    color: var(--sidebar-text) !important;
    padding: 10px 15px !important;
    font-size: 14px;
  }

  .nav-link.active {
    background: var(--sidebar-active) !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
  }
</style>

<aside class="app-sidebar shadow" data-bs-theme="dark">

 <div class="sidebar-brand d-flex justify-content-center align-items-center">
    <a href="<?= base_url('admin/dashboard'); ?>" class="brand-link text-decoration-none w-100 text-center">
        <img src="<?= base_url('public/assets/dist/assets/img/MainLOGO.png'); ?>"
            alt="Logo"
            class="sidebar-logo" />
    </a>
</div>

  <div class="sidebar-content">
    <?= $this->include('layouts/sidemenu'); ?>
  </div>

  <div class="user-profile-bottom">

<?php
$name = session()->get('staff_name') ?? 'User';
$role = session()->get('role') ?? 'Staff';

$words = explode(' ', $name);
$initials = '';
foreach ($words as $w) {
    $initials .= strtoupper(substr($w, 0, 1));
}
?>

<img src="https://ui-avatars.com/api/?name=<?= urlencode($name) ?>&background=3b82f6&color=fff">

<div class="user-info">
  <p><?= esc($name) ?></p>
  <small><?= esc($role) ?></small>
</div>

</div>

</aside>