

<script src="<?php echo base_url('public/assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js');?>"></script>
<script src="<?php echo base_url('public/assets/vendor/bootstrap/js/bootstrap.js');?>"></script>
<script src="<?php echo base_url('public/assets/vendor/nanoscroller/nanoscroller.js');?>"></script>
<script src="<?php echo base_url('public/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script src="<?php echo base_url('public/assets/vendor/jquery-placeholder/jquery-placeholder.js');?>"></script>
<script src="<?php echo base_url('public/assets/vendor/select2/js/select2.js');?>"></script>
<script src="<?php echo base_url('public/assets/vendor/fuelux/js/spinner.js');?>"></script>

<!-- Jquery Datatables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
<script src="<?php echo base_url('public/assets/vendor/datatables/extras/TableTools/JSZip-2.5.0/jszip.min.js');?>"></script>
<script src="<?php echo base_url('public/assets/vendor/datatables/extras/TableTools/pdfmake-0.1.32/pdfmake.min.js');?>"></script>
<script src="<?php echo base_url('public/assets/vendor/datatables/extras/TableTools/pdfmake-0.1.32/vfs_fonts.js');?>"></script>
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>

<script src="<?php echo base_url('public/assets/vendor/jquery-appear/jquery-appear.js');?>"></script>
<script src="<?php echo base_url('public/assets/vendor/jquery-validation/jquery.validate.js');?>"></script>
<script src="<?php echo base_url('public/assets/vendor/magnific-popup/jquery.magnific-popup.js');?>"></script>
<script src="<?php echo base_url('public/assets/vendor/screenfull/screenfull.min.js');?>"></script>
<script src="<?php echo base_url('public/assets/vendor/sweetalert/sweetalert.min.js');?>"></script>
<script src="<?php echo base_url('public/assets/js/custom.js');?>"></script>
<script src="<?php echo base_url('public/assets/js/plug.init.js');?>"></script>
<script src="<?php echo base_url('public/assets/js/app.js')?>"></script>
<script src="<?php echo base_url('public/assets/js/app.fn.js')?>"></script>

<script type="text/javascript">
    jQuery.extend(jQuery.validator.messages, {
        required: "<?=translate('this_value_is_required')?>",
        email: "<?=translate('enter_valid_email')?>",
        url: "Please enter a valid URL.",
        date: "Please enter a valid date.",
        dateISO: "Please enter a valid date (ISO).",
        number: "Please enter a valid number.",
        digits: "Please enter only digits.",
        remote: "Please fix this field.",
        creditcard: "Please enter a valid credit card number.",
        equalTo: "Please enter the same value again.",
        accept: "Please enter a value with a valid extension.",
        maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
        minlength: jQuery.validator.format("Please enter at least {0} characters."),
        rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
        range: jQuery.validator.format("Please enter a value between {0} and {1}."),
        max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
        min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
    });
</script>