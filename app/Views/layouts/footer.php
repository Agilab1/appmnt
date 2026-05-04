<style>
  .app-footer {
    margin-left: 260px;
    padding: 15px 30px;
    background-color: transparent !important;
    border-top: 1px solid #e2e8f0;
    color: #64748b;
    font-size: 13px;
    transition: margin-left 0.3s ease-in-out;
  }

  .app-footer a {
    color: #3b82f6;
    font-weight: 600;
  }


  @media (max-width: 992px) {
    .app-footer {
      margin-left: 0;
      text-align: center;
    }
  }
</style>
     <footer class="app-footer">
  <div class="container-fluid d-md-flex justify-content-between align-items-center">
    <div class="copyright-text">
      <span>&copy; 2024-<?= date('Y'); ?></span>
      <a href="#" class="text-decoration-none ms-1">Appointment Management</a>.
      <span class="d-none d-sm-inline-block ms-1">All rights reserved.</span>
    </div>
    <div class="footer-version d-none d-md-block">
      <small class="text-secondary">Version 2.0.1</small>
    </div>
  </div>
</footer>