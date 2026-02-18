<?= $this->extend('layouts/base'); ?>
<?= $this->section("content"); ?>

<div class="col-sm-2"></div>

<div class="col-sm-8">

    <!-- FLASH MESSAGES -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

   <form method="post"
      id="form"
      action="<?= ($mode == 'edit')
          ? site_url('staff/update/'.$staff->emp_code)
          : site_url('staff/save') ?>">


        <div class="row">
            <div class="col-sm-9">
                <h4 class="text-center">
                    <?= ($mode == 'edit') ? 'View Staff' : 'Create Staff' ?>
                </h4>
            </div>

            <div class="col-sm-3">
                <button type="button"
                        class="btn btn-secondary float-end"
                        onclick="history.back();">
                    <i class="fa fa-arrow-left"></i>
                </button>

                <?php if ($mode == 'create') : ?>
                    <button type="submit"
                            class="btn btn-success float-end me-3">
                        <i class="fa fa-save"></i>
                    </button>
                <?php else : ?>

                    <button type="button"
                            class="btn btn-warning float-end me-3"
                            onclick="btnToggle()">
                        <i class="fa fa-edit"></i>
                    </button>

                    <button id="saveBtn"
                            type="submit"
                            class="btn btn-success float-end me-3 d-none">
                        <i class="fa fa-save"></i>
                    </button>

                    <a href="<?= site_url('staff/delete/'.$staff->emp_code) ?>"
                       class="btn btn-danger float-end me-3"
                       onclick="return confirm('Delete this user?');">
                        <i class="fa fa-trash"></i>
                    </a>

                <?php endif; ?>
            </div>
        </div>

        <!-- STAFF ID -->
        <div class="form-group">
            <label>Staff ID</label>
            <input class="form-control"
                   type="text"
                   name="emp_code"
                   value="<?= $staff->emp_code ?? '' ?>"
                   <?= ($mode == 'edit') ? 'readonly' : '' ?>>
        </div>

        <!-- FIRST NAME -->
        <div class="form-group">
            <label>First name</label>
            <input class="form-control"
                   type="text"
                   name="first_nm"
                   value="<?= $staff->first_nm ?? '' ?>"
                   <?= ($mode == 'edit') ? 'readonly' : '' ?>>
        </div>

        <!-- LAST NAME -->
        <div class="form-group">
            <label>Last name</label>
            <input class="form-control"
                   type="text"
                   name="last_nm"
                   value="<?= $staff->last_nm ?? '' ?>"
                   <?= ($mode == 'edit') ? 'readonly' : '' ?>>
        </div>

        <!-- EMAIL -->
        <div class="form-group">
            <label>Email</label>
            <input class="form-control"
                   type="text"
                   name="email_id"
                   value="<?= $staff->email_id ?? '' ?>"
                   <?= ($mode == 'edit') ? 'readonly' : '' ?>>
        </div>

        <!-- CONTACT -->
        <div class="form-group">
            <label>Contact</label>
            <input class="form-control"
                   type="text"
                   name="cell_no"
                   value="<?= $staff->cell_no ?? '' ?>"
                   <?= ($mode == 'edit') ? 'readonly' : '' ?>>
        </div>

        <!-- ROLE -->
        <div class="form-group">
            <label>Role</label>
            <select name="role"
                    class="form-control"
                    <?= ($mode == 'edit') ? 'disabled' : '' ?>>
                <?php foreach ($types as $param => $value): ?>
                    <option value="<?= $param ?>"
                        <?= (isset($staff->role) && $staff->role == $param) ? 'selected' : '' ?>>
                        <?= $value ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- PASSWORD -->
        <div class="form-group">
            <label>Password</label>
            <input class="form-control"
                   type="password"
                   name="pass_wd"
                   <?= ($mode == 'edit') ? 'readonly' : '' ?>>
        </div>

    </form>
</div>

<div class="col-sm-2"></div>

<?= $this->endSection(); ?>

<?= $this->section("side_bar"); ?>
<div class="row">
    <a href="<?= site_url('users') ?>">Users</a>
</div>
<?= $this->endSection(); ?>

<?= $this->section('custom'); ?>
<script>
function btnToggle()
{
    document.querySelectorAll("#form input, #form select").forEach(el => {
        el.removeAttribute("readonly");
        el.removeAttribute("disabled");
    });

    document.getElementById("saveBtn").classList.remove("d-none");
}
</script>
<?= $this->endSection(); ?>
