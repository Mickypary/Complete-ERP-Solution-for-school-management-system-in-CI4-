<?php
    $db = \Config\Database::connect();
    $validation = \Config\Services::validation();
?>


<section class="panel">
    <div class="tabs-custom">
        <ul class="nav nav-tabs">
            <li>
                <a href="<?=base_url('branch')?>"><i class="fas fa-list-ul"></i> <?=translate('branch_list')?></a>
            </li>
            <li class="active">
                <a href="#edit" data-toggle="tab"><i class="far fa-edit"></i> <?=translate('edit_branch')?></a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="edit">
                <?php echo form_open(uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
                    <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $data->id; ?>">
                    <div class="form-group mt-md">
                        <label class="col-md-3 control-label"><?=translate('branch_name')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="branch_name" value="<?=set_value('branch_name', $data->name)?>" />
                            <span class="error"><?=$validation->getError('branch_name') ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('school_name')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="school_name" value="<?=set_value('school_name', $data->school_name)?>" />
                            <span class="error"><?=$validation->getError('school_name') ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('email')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="email" value="<?=set_value('email', $data->email)?>"  />
                            <span class="error"><?=$validation->getError('email') ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('mobile_no')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="mobileno" value="<?=set_value('mobileno', $data->mobileno)?>" />
                            <span class="error"><?=$validation->getError('mobileno') ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-md-3 control-label"><?=translate('currency')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="currency" value="<?=set_value('currency', $data->currency)?>" />
                            <span class="error"><?=$validation->getError('currency') ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('currency_symbol')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="currency_symbol" value="<?=set_value('currency_symbol', $data->symbol)?>" />
                            <span class="error"><?=$validation->getError('currency_symbol') ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('city')?></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="city" value="<?=set_value('city', $data->city)?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('state')?></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="state" value="<?=set_value('state', $data->state)?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-md-3 control-label"><?=translate('address')?></label>
                        <div class="col-md-6 mb-md">
                            <textarea type="text" rows="3" class="form-control" name="address" ><?=set_value('address', $data->address)?></textarea>
                        </div>
                    </div>
                    <footer class="panel-footer mt-lg">
                        <div class="row">
                            <div class="col-md-2 col-md-offset-3">
                                <button type="submit" class="btn btn-default btn-block" name="submit" value="save">
                                    <i class="fas fa-plus-circle"></i> <?=translate('update')?>
                                </button>
                            </div>
                        </div>  
                    </footer>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</section>
