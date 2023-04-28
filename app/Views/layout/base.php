<!doctype html>
<html class="fixed sidebar-left-sm <?php echo ($theme_config['dark_skin'] == 'true' ? 'dark' : 'sidebar-light');?>">
<!-- html header -->
<?php echo view('layout/header.php');?>

<body class="loading-overlay-showing" data-loading-overlay>
    <!-- page preloader -->
    <div class="loading-overlay dark">
        <div class="ring-loader">
            Loading <span></span>
        </div>
    </div>
    <section class="body">
        <!-- top navbar-->
        <?php echo view('layout/topbar.php');?>

        <div class="inner-wrapper">
            <!-- sidebar -->
            <?php 
            if (is_student_loggedin() || is_parent_loggedin()) {
                echo view('userrole/sidebar'); 
            } else {
                echo view('layout/sidebar'); 
            } 
            ?>
            <!-- page main content -->
            <section role="main" class="content-body">
                <header class="page-header">
                    <a class="page-title-icon" href="<?php echo base_url('dashboard');?>"><i class="fas fa-home"></i></a>
                    <h2><?php echo $title;?></h2>
                </header>
                <?= $this->renderSection('main'); ?>
            </section>
        </div>
    </section>

    <!-- JS Script -->
    <?php echo view('layout/script.php');?>
    
    <?php
    $alertclass = "";
    if($this->session->getFlashdata('alert-message-success')){
        $alertclass = "success";
    } else if ($this->session->getFlashdata('alert-message-error')){
        $alertclass = "error";
    } else if ($this->session->getFlashdata('alert-message-info')){
        $alertclass = "info";
    }
    if($alertclass != ''):
        $alert_message = $this->session->getFlashdata('alert-message-'. $alertclass);
    ?>
        <script type="text/javascript">
            swal({
                toast: true,
                position: 'top-end',
                type: '<?php echo $alertclass?>',
                title: '<?php echo $alert_message?>',
                confirmButtonClass: 'btn btn-default',
                buttonsStyling: false,
                timer: 8000
            })
        </script>
    <?php endif; ?>

    <!-- sweetalert box -->
    <script type="text/javascript">
        function confirm_modal(delete_url) {
            swal({
                title: "<?php echo translate('are_you_sure')?>",
                text: "<?php echo translate('delete_this_information')?>",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn btn-default swal2-btn-default",
                cancelButtonClass: "btn btn-default swal2-btn-default",
                confirmButtonText: "<?php echo translate('yes_continue')?>",
                cancelButtonText: "<?php echo translate('cancel')?>",
                buttonsStyling: false,
                footer: "<?php echo translate('deleted_note')?>"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: delete_url,
                        type: "POST",
                        success:function(data) {
                            swal({
                            title: "<?php echo translate('deleted')?>",
                            text: "<?php echo translate('information_deleted')?>",
                            buttonsStyling: false,
                            showCloseButton: true,
                            focusConfirm: false,
                            confirmButtonClass: "btn btn-default swal2-btn-default",
                            type: "success"
                            }).then((result) => {
                                if (result.value) {
                                    location.reload();
                                }
                            });
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>