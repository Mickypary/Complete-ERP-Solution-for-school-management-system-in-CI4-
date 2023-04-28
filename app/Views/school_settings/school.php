<?php

use App\Libraries\App_lib;

$this->db = \Config\Database::connect();
$validation = \Config\Services::validation();
$this->app_lib = new App_lib();
?>



<?php if (is_superadmin_loggedin() && empty($branchID)) { ?>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-school"></i> <?=translate('school') . " " . translate('list')?></h4>
            </header>
            <div class="panel-body">
                    <table class="table table-bordered table-hover table-condensed mb-none table_default">
                        <thead>
                            <tr>
                                <th width="50"><?=translate('sl')?></th>
                                <th><?=translate('branch_name')?></th>
                                <th><?=translate('school_name')?></th>
                                <th><?=translate('email')?></th>
                                <th><?=translate('address')?></th>
                                <th><?=translate('action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $count = 1;
                                $branchs = $this->db->table('branch')->get()->getResult();
                                foreach($branchs as $row):
                            ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo $row->name;?></td>
                                <td><?php echo $row->school_name;?></td>
                                <td><?php echo $row->email;?></td>
                                <td><?php echo $row->address;?></td>
                                <td class="min-w-c">
                                    <!--update link-->
                                    <a href="<?=base_url('school_settings?branch_id='.$row->id)?>" class="btn btn-default btn-circle">
                                        <i class="fas fa-sliders-h"></i> Configuration
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
            </div>
        </section>
    </div>
</div>
<?php } ?>
<?php if (!empty($branchID)) { ?>
<div class="row">
    <div class="col-md-3">
        <?php echo view('school_settings/sidebar'); ?>
    </div>
    <div class="col-md-9">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-school"></i> <?=translate('school_setting')?></h4>
            </header>
            <form class="form-horizontal frm-submit-msg">
                <?=$this->app_lib->generateCSRF()?>
                <div class="panel-body">
                    <div class="form-group mt-md">
                        <label class="col-md-3 control-label"><?=translate('branch_name')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="branch_name" value="<?=$school['name']?>" />
                            <span class="error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('school_name')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="school_name" value="<?=$school['school_name']?>" />
                            <span class="error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('email')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="email" class="form-control" name="email" value="<?=$school['email']?>" />
                            <span class="error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('mobile_no')?></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="mobileno" value="<?=$school['mobileno']?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-md-3 control-label"><?=translate('currency')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="currency" value="<?=$school['currency']?>" />
                            <span class="error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('currency_symbol')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="currency_symbol" value="<?=$school['symbol']?>" />
                            <span class="error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('city')?></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="city" value="<?=$school['city']?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('state')?></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="state" value="<?=$school['state']?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-md-3 control-label"><?=translate('address')?></label>
                        <div class="col-md-6 mb-md">
                            <textarea type="text" rows="3" class="form-control" name="address"><?=$school['address']?></textarea>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-3 col-sm-offset-3">
                            <button type="submit" class="btn btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                                <i class="fas fa-plus-circle"></i> <?=translate('save');?>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
     </div>
</div>
<?php } ?>