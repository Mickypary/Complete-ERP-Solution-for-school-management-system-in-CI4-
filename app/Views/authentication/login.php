<?php

$validation = \Config\Services::validation();
$this->session = \Config\Services::session();


?>




<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="keywords" content="">
    <meta name="description" content="Mickypary School Management System">
    <meta name="author" content="MickyparyCoder">
    <title><?php echo translate('login');?></title>
    <link rel="shortcut icon" href="<?php echo base_url('public/assets/images/favicon.png');?>">
    
    <!-- Web Fonts  -->
    <link href="<?php echo is_secure('fonts.googleapis.com/css?family=Signika:300,400,600,700');?>" rel="stylesheet"> 
    <link rel="stylesheet" href="<?php echo base_url('public/assets/vendor/bootstrap/css/bootstrap.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('public/assets/vendor/font-awesome/css/all.min.css'); ?>">
    <script src="<?php echo base_url('public/assets/vendor/jquery/jquery.js');?>"></script>
    
    <!-- sweetalert js/css -->
    <link rel="stylesheet" href="<?php echo base_url('public/assets/vendor/sweetalert/sweetalert-custom.css');?>">
    <script src="<?php echo base_url('public/assets/vendor/sweetalert/sweetalert.min.js');?>"></script>
    <!-- login page style css -->
    <link rel="stylesheet" href="<?php echo base_url('public/assets/login_page/css/style.css');?>">
    <script type="text/javascript">
        var base_url = '<?php echo base_url() ?>';
    </script>
</head>
    <body>
        <div class="auth-main">
            <div class="container">
                <div class="slideIn">
                    <!-- image and information -->
                    <div class="col-lg-4 col-lg-offset-1 col-md-4 col-md-offset-1 col-sm-12 col-xs-12 no-padding fitxt-center">
                        <div class="image-area">
                        <div class="content">
                            <div class="image-hader">
                                <h2><?php echo translate('welcome_to');?></h2>
                            </div>
                            <div class="center img-hol-p">
                                <img src="<?php echo base_url('public/uploads/app_image/logo.png');?>" height="60" alt="MickyparyCoder School">
                            </div>
                            <div class="address">
                                <p><?php echo $global_config['address'];?></p>
                            </div>          
                            <div class="f-social-links center">
                                <a href="<?php echo $global_config['facebook_url'];?>" target="_blank">
                                    <span class="fab fa-facebook-f"></span>
                                </a>
                                <a href="<?php echo $global_config['twitter_url'];?>" target="_blank">
                                    <span class="fab fa-twitter"></span>
                                </a>
                                <a href="<?php echo $global_config['linkedin_url'];?>" target="_blank">
                                    <span class="fab fa-linkedin-in"></span>
                                </a>
                                <a href="<?php echo $global_config['youtube_url'];?>" target="_blank">
                                    <span class="fab fa-youtube"></span>
                                </a>
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Login -->
                    <div class="col-lg-6 col-lg-offset-right-1 col-md-6 col-md-offset-right-1 col-sm-12 col-xs-12 no-padding">
                        <div class="sign-area">
                            <div class="sign-hader">
                                <img src="<?php echo base_url('public/uploads/app_image/logo.png');?>" height="54" alt="">
                                <h2><?php echo $global_config['institute_name'];?></h2>
                            </div>
                            <?php echo form_open(uri_string()); ?>
                                <div class="form-group <?php if ($validation->getError('email')) echo $this->validation->getError('email'); ?>">
                                    <div class="input-group input-group-icon">
                                        <span class="input-group-addon">
                                            <span class="icon">
                                                <i class="far fa-user"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control" name="email" value="<?php echo set_value('email');?>" placeholder="<?php echo translate('email');?>" />
                                    </div>
                                    <span class="error"><?= $validation->getError('email') ?></span>
                                </div>
                                <div class="form-group <?php if ($validation->getError('password')) echo $validation->getError('password'); ?>">
                                    <div class="input-group input-group-icon">
                                        <span class="input-group-addon">
                                            <span class="icon"><i class="fas fa-unlock-alt"></i></span>
                                        </span>
                                        <input type="password" class="form-control input-rounded" name="password" placeholder="<?php echo translate('password');?>" />
                                    </div>
                                    <span class="error"><?= $validation->getError('password'); ?></span>
                                </div>

                                <div class="forgot-text">
                                    <div class="checkbox-replace">
                                        <label class="i-checks"><input type="checkbox" name="remember" id="remember"><i></i> <?php echo translate('remember');?></label>
                                    </div>
                                    <div class="">
                                        <a href="<?php echo base_url('authentication/forgot');?>"><?php echo translate('lose_your_password');?></a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="btn_submit" class="btn btn-block btn-round">
                                        <i class="fas fa-sign-in-alt"></i> <?php echo translate('login');?>
                                    </button>
                                </div>
                                <div class="sign-footer">
                                    <p><?php echo $global_config['footer_text'];?></p>
                                </div>
                            <?php echo form_close();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="<?php echo base_url('public/assets/vendor/bootstrap/js/bootstrap.js');?>"></script>
        <script src="<?php echo base_url('public/assets/vendor/jquery-placeholder/jquery-placeholder.js');?>"></script>
        <!-- backstretch js -->
        <script src="<?php echo base_url('public/assets/login_page/js/jquery.backstretch.min.js');?>"></script>
        <script src="<?php echo base_url('public/assets/login_page/js/custom.js');?>"></script>

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
                    type: '<?php echo $alertclass;?>',
                    title: '<?php echo $alert_message;?>',
                    confirmButtonClass: 'btn btn-default',
                    buttonsStyling: false,
                    timer: 8000
                })
            </script>
        <?php endif; ?>
    </body>
</html>