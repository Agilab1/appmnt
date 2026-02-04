<?php if (session()->getFlashdata('status')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('status') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        </button>
  </div>
</div>
<?php endif;?>