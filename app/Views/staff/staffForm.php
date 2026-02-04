<?= $this->extend('layouts/base'); ?>
<?= $this->section("content"); ?>
<div class="col-sm-2">
</div>
<div class="col-sm-8">
    <form method="post" id="form">
        <div class="row">
            <div class="col-sm-9">
                <h4 class="text-center">Staff Create</h4>
            </div>
            <div class="col-sm-3">
                <button type="button" class="btn btn-secondary float-end" onclick="history.back();"> <i class="fa fa-arrow-left"></i> </button>

                <?php if ($mode == 'create') : ?>
                    <!-- CREATE MODE -->
                    <button id="saveBtn" type="submit" class="btn btn-success float-end me-3"> <i class="fa fa-save"></i> </button>
                <?php else : ?>
                    <!-- VIEW MODE -->
                    <button id="editBtn" type="button" class="btn btn-warning float-end me-3" onclick="btnToggle(this)"> <i class="fa fa-edit"></i> </button>

                    <button id="saveBtn" type="submit" class="btn btn-success float-end me-3 d-none"> <i class="fa fa-save"></i> </button>

                    <a href="staff/delete/<?= $staff->emp_code ?>" class="btn btn-danger float-end me-3" onclick="return confirm('Are you sure?');"> <i class="fa fa-trash"></i> </a>
                <?php endif; ?>
            </div>

        </div>
        <div class="form-group">
            <label class="form-label">Staff ID</label>
            <input class="form-control" type="text" name="emp_code" value="<?= set_value('emp_code', isset($staff->emp_code) ? $staff->emp_code : '') ?>" autocomplete="off">
        </div>
        <div class="form-group">
            <label class="form-label">First name</label>
            <input class="form-control" type="text" name="first_nm" value="<?= set_value('first_nm', isset($staff->first_nm) ? $staff->first_nm : '') ?>" autocomplete="off">
        </div>
        <div class="form-group">
            <label class="form-label">Last name</label>
            <input class="form-control" type="text" name="last_nm" value="<?= set_value('last_nm', isset($staff->last_nm) ? $staff->last_nm : '') ?>" autocomplete="off">
        </div>
        <div class="form-group">
            <label class="form-label">Email ID</label>
            <input class="form-control" type="mail" name="email_id" value="<?= set_value('email_id', isset($staff->email_id) ? $staff->email_id : '') ?>" autocomplete="off">
        </div>

        <div class="form-group">
            <label class="form-label">Contact No</label>
            <input class="form-control" type="text" name="cell_no" value="<?= set_value('cell_no', isset($staff->cell_no) ? $staff->cell_no : '') ?>" autocomplete="off">
        </div>
        <div class="form-group">
            <label class="form-label">Role</label>
            <select name="role" id="role" class="form-control">
                <option value="">Select Role</option>
                <?php foreach ($types as $param => $value): ?>
                    <option value="<?= $param ?>"
                        <?= (isset($staff->role) && $staff->role === $param) ? 'selected' : '' ?>>
                        <?= $value ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Password </label>
            <input class="form-control" type="password" name="pass_wd" id="pass_wd" value="<?= set_value('pass_wd', '', false) ?>">
        </div>
        <div class="form-group">
            <label class="form-label">Confirm Pass </label>
            <input class="form-control" type="password" name="cpas_wd" id="cpas_wd" value="<?php set_value('cpas_wd', '', false) ?>">
        </div>
    </form>
</div>
<div class="col-sm-2">

</div>
<?= $this->endSection(); ?>
<?= $this->section("side_bar"); ?>
<div class="row">
    <a href="users" class="">Users</a>
</div>
<?= $this->endSection(); ?>