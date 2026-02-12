<style>
  .app-sidebar {
    position: fixed !important;
    top: 0;
    left: 0;
    bottom: 0;
    width: 280px;
    overflow-y: auto;
    overflow-x: hidden;
  }

  .app-main {
    margin-left: 250px;
  }

  .sidebar-brand {
    padding: 25px 10px 25px;
    margin: 0;
    text-align: center;
    border-bottom: none !important;
  }
  .brand-link {
    display: block;
    padding: 0 !important;
    margin: 0;
    border: none !important;
  }
  .sidebar-logo {
    width: 100%;
    height: auto;
    display: block;
  }
</style>
<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <!--begin::Brand Link-->
    <a href="./index.html" class="brand-link">
      <!--begin::Brand Image-->
      <!-- <img
        src="public/assets/dist/assets/img/AdminLTELogo.png"
        alt="AdminLTE Logo"
        class="brand-image opacity-75 shadow" /> -->
      <!--end::Brand Image-->
      <!--begin::Brand Text-->
      <div class="sidebar-brand">

        <a href="./index.html" class="brand-link text-center">

          <img src="<?= base_url('public/assets/dist/assets/img/Appoitment.png'); ?>"
            alt="Logo"
            class="sidebar-logo" />

        </a>

      </div>



      <!--end::Brand Text-->
    </a>
    <!--end::Brand Link-->
  </div>
  <!--end::Sidebar Brand-->
  <?= $this->include('layouts/sidemenu'); ?>
</aside>
<!--end::Sidebar-->