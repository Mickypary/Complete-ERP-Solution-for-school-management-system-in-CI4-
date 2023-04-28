<?php

$this->validation = \Config\Services::validation();


?>




<section class="panel">
    <div class="tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#list" data-toggle="tab">
                    <i class="fas fa-unlock-alt"></i> <?php echo translate('change') . " " . translate('password'); ?>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane box active" id="list">
                <?php echo form_open(uri_string(), array('class' => 'form-horizontal form-bordered')); ?>
                    <div class="form-group mt-xs">
                        <label class="col-md-3 control-label"><?php echo translate('current_password'); ?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" name="current_password" value="<?php echo set_value('current_password'); ?>" />
                            <span class="error"><?php echo $this->validation->getError('current_password'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?php echo translate('new_password'); ?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" name="new_password" value="<?php echo set_value('new_password'); ?>" />
                            <span class="error"><?php echo $this->validation->getError('new_password'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?php echo translate('confirm_password'); ?> <span class="required">*</span></label>
                        <div class="col-md-6 mb-md">
                            <input type="password" class="form-control" name="confirm_password" value="<?php echo set_value('confirm_password'); ?>" />
                            <span class="error"><?php echo $this->validation->getError('confirm_password'); ?></span>
                        </div>
                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-2 col-md-offset-3">
                                <button type="submit" class="btn btn-default btn-block"><i class="fas fa-key"></i> <?php echo translate('update'); ?></button>
                            </div>
                        </div>  
                    </footer>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</section>