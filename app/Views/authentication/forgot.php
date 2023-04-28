<?php

$this->validation = \Config\Services::validation();
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
    <meta name="author" content="ParyCoder">
    <title><?php echo translate('password_restoration');?></title>
    <link rel="shortcut icon" href="<?php echo base_url('public/assets/images/favicon.png');?>">

    <!-- Web Fonts  -->
    <link href="<?php echo is_secure('fonts.googleapis.com/css?family=Signika:300,400,600,700');?>" rel="stylesheet"> 
    <link rel="stylesheet" href="<?php echo base_url('public/assets/vendor/bootstrap/css/bootstrap.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('public/assets/vendor/font-awesome/css/all.min.css'); ?>">
    <script src="<?php echo base_url('assets/vendor/jquery/jquery.js');?>"></script>
    
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
                                <img src="<?php echo base_url('public/uploads/app_image/logo.png');?>" height="60" alt="ParyCoder School">
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
                            <div class="sign-hader pt-md">
                                <img src="<?php echo base_url('public/uploads/app_image/logo.png');?>" height="54" alt="" >
                                <h2><?=$global_config['institute_name']?></h2>
                            </div>
                                <?php 
                                    if($this->session->getFlashdata('reset_res')){
                                        if($this->session->getFlashdata('reset_res') == TRUE){
                                            echo '<div class="alert-msg">Password reset email sent successfully. Check email</div>';
                                        }elseif($this->session->getFlashdata('reset_res') == FALSE){
                                            echo '<div class="alert-msg danger">You entered the wrong email address</div>';
                                        }
                                    }
                                ?>
                                <div class="forgot-header">
                                    <h4><i class="fas fa-fingerprint"></i> <?php echo translate('password_restoration');?></h4>
                                    Enter your email and you will receive reset instructions
                                </div>
                                <?php echo form_open(uri_string()); ?>
                                <div class="form-group <?php if ($this->validation->getError('username')) echo 'has-error'; ?>">
                                    <div class="input-group input-group-icon">
                                        <span class="input-group-addon">
                                            <span class="icon">
                                                <i class="fas fa-unlock-alt"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control" name="username" value="<?=set_value('username')?>" autocomplete="off" placeholder="<?php echo translate('email');?>" />
                                    </div>
                                    <span class="error"><?php echo $this->validation->getError('username'); ?></span>
                                </div>

                                <div class="form-group">
                                    <button type="submit" id="btn_submit" class="btn btn-block ladda-button btn-round">
                                        <i class="far fa-paper-plane"></i> <?php echo translate('forgot');?>
                                    </button>
                                </div>
                                <div class="text-center">
                                    <a href="<?php echo base_url('authentication');?>"><i class="fas fa-long-arrow-alt-left"></i> <?php echo translate('back_to_login');?></a>
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
    </body>
</html>