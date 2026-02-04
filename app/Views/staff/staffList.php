<?=  $this->extend('layouts/base'); ?>

<?=  $this->section("content"); ?>
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-4">
                    <h5 class="card-title">List of Users</h1>
                </div>
                <div class="col-sm-8">
                    <a href="staff/create" class="btn btn-primary float-end">Create User</a>
                </div>
            </div>  
        </div>
        <div class="card-body">
            <table class="table table-stripped table-bordered">
                <tr>
                    <!-- <th class="text-nowrap">UserID</th> -->
                    <th>SR No</th>
                    <th>Staff ID</th>
                    <th>Staff Name</th>
                    <th>Email ID</th>
                    <th>Mobile No</th>
                    
                    <!-- <th>Created At</th> -->
                </tr>
                <?php foreach($staffs as  $count =>  $staff): ?>
                    <tr>
                        <td><?= ++$count ?></td>
                        <td><a href="staff/update/<?= $staff->emp_code  ?>"><?= $staff->emp_code ?></a></td>
                        <td><?= $staff->first_nm . ' ' . $staff->last_nm ?></td>
                        <td><?= $staff->email_id ?></td>
                        <td><?= $staff->cell_no ?></td>
                        
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>
<?=  $this->endSection(); ?>
<?=  $this->section("side_bar"); ?>
<div class="row">
    <a href="users" class="">Users</a>
</div> 
<?= $this->endSection(); ?>