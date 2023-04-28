<?= $this->extend('layout/base'); ?>




<?= $this->section('main'); ?>

    <?php if(isset($sub_page)): ?>
        <?= $this->include($sub_page); ?>
    <?php endif; ?>


    






<?= $this->endSection(); ?>




<!-- <?php //if(isset($sub_page)): ?>
        <?php //echo view($sub_page); ?>
    <?php //endif; ?> -->