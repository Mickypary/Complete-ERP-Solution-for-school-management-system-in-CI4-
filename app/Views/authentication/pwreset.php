<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<title>Preskool - Forgot Password</title>

<link rel="shortcut icon" href="<?= base_url(); ?>public/assets/img/favicon.png">

<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?= base_url(); ?>public/assets/plugins/bootstrap/css/bootstrap.min.css">

<link rel="stylesheet" href="<?= base_url(); ?>public/assets/plugins/feather/feather.css">

<link rel="stylesheet" href="<?= base_url(); ?>public/assets/plugins/icons/flags/flags.css">

<link rel="stylesheet" href="<?= base_url(); ?>public/assets/plugins/fontawesome/css/fontawesome.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>public/assets/plugins/fontawesome/css/all.min.css">

<link rel="stylesheet" href="<?= base_url(); ?>public/assets/css/style.css">
</head>
<body>

<div class="main-wrapper login-body">
<div class="login-wrapper">
<div class="container">
<div class="loginbox">
<div class="login-left">
<img class="img-fluid" src="<?= base_url(); ?>public/assets/img/login.png" alt="Logo">
</div>
<div class="login-right">
<div class="login-right-wrap">
<h1><?= translate('change_password'); ?></h1>
<p class="account-subtitle">Let Us Help You</p>

<form action="<?= base_url() ?>authentication/pwreset/<?= $id ?>" method="post">

    <?php if(isset($validation)): ?>
        <div class="alert alert-danger"><?= $validation->listErrors(); ?></div>
    <?php endif; ?>
                                
<div class="form-group">
<label>New Password <span class="login-danger">*</span></label>
<input class="form-control" type="password" name="password">
<span class="profile-views"><i class="fas fa-envelope"></i></span>
</div>
<div class="form-group">
<label>Confirm Password <span class="login-danger">*</span></label>
<input class="form-control" type="password" name="c_password">
<span class="profile-views"><i class="fas fa-envelope"></i></span>
</div>
<div class="form-group">
<button class="btn btn-primary btn-block" type="submit"><?= translate('change_password'); ?></button>
</div>
<div class="form-group mb-0">
<!-- <button class="btn btn-primary primary-reset btn-block" type="submit">Login</button> -->
<a class="btn btn-primary primary-reset btn-block" href="<?= base_url() ?>authentication"><?= translate('login'); ?></a>
</div>
</form>

</div>
</div>
</div>
</div>
</div>
</div>


<script src="<?= base_url(); ?>public/assets/js/jquery-3.6.0.min.js"></script>

<script src="<?= base_url(); ?>public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?= base_url(); ?>public/assets/js/feather.min.js"></script>

<script src="<?= base_url(); ?>public/assets/js/script.js"></script>
</body>
</html>