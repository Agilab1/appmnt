<script>
    $('.select2').select2();

    var mode = "<?= $mode ?? '' ?>";

    if (mode === 'view') {
        $('#form input, #form select, #form textarea').prop('disabled', true);
        $('#saveBtn').addClass('d-none');
    }

    $(document).ready(function() {

        var mode = "<?= $mode ?? '' ?>";

        if (mode === 'view') {
            $('#form input, #form select').prop('disabled', true);
            $('#saveBtn').addClass('d-none');
            $('#pass_wd, #cpas_wd').closest('.form-group').hide();
        }
    });

    function btnToggle(btn) {

        // enable form
        $('#form input, #form select').prop('disabled', false);

        // show save button
        $('#saveBtn').removeClass('d-none');

        // hide edit button
        $(btn).addClass('d-none');

        // show password fields (FULL form-group)
        $('#pass_wd, #cpas_wd').closest('.form-group').show();
    }
</script>

<!-- <script>
    var mode = "<?= $mode ?? '' ?>";

    // VIEW MODE → disable form
    if (mode === 'view') {
        $('#form input, #form select, #form textarea').prop('disabled', true);
        $('#saveBtn').addClass('d-none');
    }

    function enableEdit() {

        // enable form
        $('#form input, #form select, #form textarea').prop('disabled', false);

        // show save
        $('#saveBtn').removeClass('d-none');

        // hide edit
        $('#editBtn').addClass('d-none');

        // optional: password fields show only on edit
        $('#pass_wd, #cpas_wd').show();
    }
</script> -->