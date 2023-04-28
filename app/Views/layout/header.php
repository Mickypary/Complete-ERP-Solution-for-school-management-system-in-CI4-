<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Mickypary School Management System">
    <meta name="author" content="Mickypary">
    <title><?php echo $title;?></title>
    <link rel="shortcut icon" href="<?php echo base_url('public/assets/images/favicon.png');?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- include stylesheet -->
    <?php include 'stylesheet.php';?>

    <?php
    if(isset($headerelements)) {
        foreach ($headerelements as $type => $element) {
            if($type == 'css') {
                if(count($element)) {
                    foreach ($element as $keycss => $css) {
                        echo '<link rel="stylesheet" href="'. base_url('public/assets/' . $css) . '">' . "\n";
                    }
                }
            } elseif($type == 'js') {
                if(count($element)) {
                    foreach ($element as $keyjs => $js) {
                        echo '<script type="text/javascript" src="' . base_url('public/assets/' . $js). '"></script>' . "\n";
                    }
                }
            }
        }
    }
    ?>
    <!-- ramom css -->
    <link rel="stylesheet" href="<?php echo base_url('public/assets/css/ramom.css');?>">
    <?php if ($theme_config["border_mode"] == 'false'): ?>
        <link rel="stylesheet" href="<?php echo base_url('public/assets/css/skins/square-borders.css');?>">
    <?php endif; ?>
    

    <!-- If user have enabled CSRF proctection this function will take care of the ajax requests and append custom header for CSRF -->

    <script type="text/javascript">
        var base_url = '<?php echo base_url(); ?>';
        var csrfData = <?php echo json_encode(csrf_jquery_token()); ?>;
        $(function($) {
            $.ajaxSetup({
                data: csrfData
            });
        });
    </script>

</head>