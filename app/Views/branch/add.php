<?php
    $this->db = \Config\Database::connect();
    $validation = \Config\Services::validation();
?>


<section class="panel">
    <div class="tabs-custom">
        <ul class="nav nav-tabs">
            <li class="<?=(empty($validation_error) ? 'active' : '') ?>">
                <a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?=translate('branch_list')?></a>
            </li>
            <li class="<?=(!empty($validation_error) ? 'active' : '') ?>">
                <a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?=translate('create_branch')?></a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="list" class="tab-pane <?=(empty($validation_error) ? 'active' : '')?>">
                <div class="mb-md">
                    <table class="table table-bordered table-hover table-condensed mb-none table-export">
                        <thead>
                            <tr>
                                <th width="50"><?=translate('sl')?></th>
                                <th><?=translate('branch_name')?></th>
                                <th><?=translate('school_name')?></th>
                                <th><?=translate('email')?></th>
                                <th><?=translate('mobile_no')?></th>
                                <th><?=translate('currency')?></th>
                                <th><?=translate('symbol')?></th>
                                <th><?=translate('city')?></th>
                                <th><?=translate('state')?></th>
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
                                <td><?php echo $row->mobileno;?></td>
                                <td><?php echo $row->currency;?></td>
                                <td><?php echo $row->symbol;?></td>
                                <td><?php echo $row->city;?></td>
                                <td><?php echo $row->state;?></td>
                                <td><?php echo $row->address;?></td>
                                <td class="min-w-c">
                                    <!--update link-->
                                    <a href="<?=base_url('branch/edit/'.$row->id)?>" class="btn btn-default btn-circle icon">
                                        <i class="fas fa-pen-nib"></i>
                                    </a>
                                    <!-- delete link -->
                                    <?php echo btn_delete('branch/delete_data/' . $row->id);?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane <?=(!empty($validation_error) ? 'active' : '')?>" id="create">
                <?php echo form_open(uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
                    <div class="form-group mt-md">
                        <label class="col-md-3 control-label"><?=translate('branch_name')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="branch_name" value="<?=set_value('branch_name')?>" />
                            <span class="error"><?=$validation->getError('branch_name') ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('school_name')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="school_name" value="<?=set_value('school_name')?>" />
                            <span class="error"><?=$validation->getError('school_name') ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('email')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="email" value="<?=set_value('email')?>" />
                            <span class="error"><?=$validation->getError('email') ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('mobile_no')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="mobileno" value="<?=set_value('mobileno')?>">
                            <span class="error"><?=$validation->getError('mobileno') ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-md-3 control-label"><?=translate('currency')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="currency" value="<?=set_value('currency')?>" />
                            <span class="error"><?=$validation->getError('currency') ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('currency_symbol')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="currency_symbol" value="<?=set_value('currency_symbol')?>" />
                            <span class="error"><?=$validation->getError('currency_symbol'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('city')?></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="city" value="<?=set_value('city')?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('state')?></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="state" value="<?=set_value('state')?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-md-3 control-label"><?=translate('address')?></label>
                        <div class="col-md-6 mb-md">
                            <textarea type="text" rows="3" class="form-control" name="address" ><?=set_value('address')?></textarea>
                        </div>
                    </div>
                    <footer class="panel-footer mt-lg">
                        <div class="row">
                            <div class="col-md-2 col-md-offset-3">
                                <button type="submit" class="btn btn-default btn-block" name="submit" value="save">
                                    <i class="fas fa-plus-circle"></i> <?=translate('save')?>
                                </button>
                            </div>
                        </div>  
                    </footer>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</section>