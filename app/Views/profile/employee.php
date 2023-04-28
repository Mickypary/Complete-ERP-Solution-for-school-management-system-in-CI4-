<?php
    
    use App\Libraries\App_lib;
    $this->validation = \Config\Services::validation();
    $this->app_lib = new App_lib();


?>





<?php $disabled = (is_admin_loggedin() ?  '' : 'disabled');?>
<div class="row appear-animation" data-appear-animation="<?=$global_config['animations'] ?>">
    <div class="col-md-12 mb-lg">
        <div class="profile-head social">
            <div class="col-md-12 col-lg-4 col-xl-3">
                <div class="image-content-center user-pro">
                    <div class="preview">
                        <ul class="social-icon-one">
                            <li><a href="<?=empty($staff['facebook_url']) ? '#' : $staff['facebook_url']?>"><span class="fab fa-facebook-f"></span></a></li>
                            <li><a href="<?=empty($staff['twitter_url']) ? '#' : $staff['twitter_url']?>"><span class="fab fa-twitter"></span></a></li>
                            <li><a href="<?=empty($staff['linkedin_url']) ? '#' : $staff['linkedin_url']?>"><span class="fab fa-linkedin-in"></span></a></li>
                        </ul>
                        <img src="<?=get_image_url('staff', $staff['photo'])?>">
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-5 col-xl-5">
                <h5><?php echo $staff['name']; ?></h5>
                <p><?php echo ucfirst($staff['role'])?> / <?php echo esc($staff['designation_name']); ?></p>
                <ul>
                    <li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?=translate('department')?>"><i class="fas fa-user-tie"></i></div> <?=(!empty($staff['department_name']) ? $staff['department_name'] : 'N/A'); ?></li>
                    <li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?=translate('birthday')?>"><i class="fas fa-birthday-cake"></i></div> <?=_d($staff['birthday'])?></li>
                    <li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?=translate('joining_date')?>"><i class="far fa-calendar-alt"></i></div> <?=_d($staff['joining_date'])?></li>
                    <li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?=translate('mobile_no')?>"><i class="fas fa-phone"></i></div> <?=(!empty($staff['mobileno']) ? $staff['mobileno'] : 'N/A'); ?></li>
                    <li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?=translate('email')?>"><i class="far fa-envelope"></i></div> <?=$staff['email']?></li>
                    <li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?=translate('present_address')?>"><i class="fas fa-home"></i></div> <?=(!empty($staff['present_address']) ? $staff['present_address'] : 'N/A'); ?></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('profile'); ?></h4>
            </header>
            <?php echo form_open_multipart(uri_string()); ?>
                <div class="panel-body">
                    <fieldset>
                        <input type="hidden" name="staff_id" id="staff_id" value="<?php echo $staff['id']; ?>">
                        <!-- employee details -->
                        <div class="headers-line mt-md">
                            <i class="fas fa-user-check"></i> <?=translate('employee_details')?>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('name')?> <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="far fa-user"></i></span>
                                        <input class="form-control" name="name" type="text" value="<?=set_value('name', $staff['name'])?>" />
                                    </div>
                                    <span class="error"><?= $this->validation->getError('name'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('gender')?></label>
                                    <?php
                                        $array = array(
                                            "" => translate('select'),
                                            "male" => translate('male'),
                                            "female" => translate('female')
                                        );
                                        echo form_dropdown("sex", $array, set_value('sex', $staff['sex']), "class='form-control' data-plugin-selectTwo
                                        data-width='100%' data-minimum-results-for-search='Infinity'");
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('religion')?></label>
                                    <input type="text" class="form-control" name="religion" value="<?=set_value('religion', $staff['religion'])?>">
                                </div>
                            </div>
                            <div class="col-md-4 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('blood_group')?></label>
                                    <?php
                                        $bloodArray = $this->app_lib->getBloodgroup();
                                        echo form_dropdown("blood_group", $bloodArray, set_value('blood_group', $staff['blood_group']), "class='form-control populate' data-plugin-selectTwo
                                        data-width='100%' data-minimum-results-for-search='Infinity' ");
                                    ?>
                                </div>
                            </div>

                            <div class="col-md-4 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('birthday')?> </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fas fa-birthday-cake"></i></span>
                                        <input class="form-control" name="birthday" value="<?=set_value('birthday', $staff['birthday'])?>" data-plugin-datepicker data-plugin-options='{ "startView": 2 }' autocomplete="off" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('mobile_no')?> <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fas fa-phone-volume"></i></span>
                                        <input class="form-control" name="mobile_no" type="text" value="<?=set_value('mobile_no', $staff['mobileno'])?>" />
                                    </div>
                                    <span class="error"><?php echo $this->validation->getError('mobile_no'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('present_address')?> <span class="required">*</span></label>
                                    <textarea class="form-control" rows="2" name="present_address" placeholder="<?=translate('present_address')?>" ><?=set_value('present_address', $staff['present_address'])?></textarea>
                                    <span class="error"><?php echo $this->validation->getError('present_address'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('permanent_address')?></label>
                                    <textarea class="form-control" rows="2" name="permanent_address" placeholder="<?=translate('permanent_address')?>" ><?=set_value('permanent_address', $staff['permanent_address'])?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-md">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="input-file-now"><?=translate('profile_picture')?></label>
                                    <input type="file" name="user_photo" class="dropify" data-default-file="<?=get_image_url('staff', $staff['photo'])?>"/>
                                    <span class="error"><?php echo $this->validation->getError('user_photo'); ?></span>
                                </div>
                            </div>
                            <input type="hidden" name="old_user_photo" value="<?=esc($staff['photo'])?>">
                            
                        </div>

                        <!-- login details -->
                        <div class="headers-line">
                            <i class="fas fa-user-lock"></i> <?=translate('login_details')?>
                        </div>

                        <div class="row mb-lg">
                            <div class="col-md-12 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('email')?> <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="far fa-envelope-open"></i></span>
                                        <input type="text" class="form-control" name="email" id="email" value="<?=set_value('email', esc($staff['email']))?>" />
                                    </div>
                                    <span class="error"><?php echo $this->validation->getError('email'); ?></span>
                                </div>
                            </div>
                        </div>
<?php if (!is_superadmin_loggedin()) { ?>
                        <!-- academic details-->
                        <div class="headers-line">
                            <i class="fas fa-school"></i> <?=translate('academic_details')?>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
                                    <?php
                                        $arrayBranch = $this->app_lib->getSelectList('branch');
                                        echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id', $staff['branch_id']), "class='form-control' id='branch_id' disabled
                                        data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                                    ?>
                                    <span class="error"><?php echo $this->validation->getError('branch_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('designation')?> <span class="required">*</span></label>
                                    <?php
                                        $designation_list = $this->app_lib->getDesignation($staff['branch_id']);
                                        echo form_dropdown("designation_id", $designation_list, set_value('designation_id', $staff['designation']), "class='form-control' id='designation_id' $disabled
                                        data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                                    ?>
                                    <span class="error"><?php echo $this->validation->getError('designation_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('department')?> <span class="required">*</span></label>
                                    <?php
                                        $department_list = $this->app_lib->getDepartment($staff['branch_id']);
                                        echo form_dropdown("department_id", $department_list, set_value('department_id', $staff['department']), "class='form-control' id='department_id' $disabled
                                        data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                                    ?>
                                    <span class="error"><?php echo $this->validation->getError('department_id'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-lg">
                            <div class="col-md-4 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('joining_date')?> <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fas fa-birthday-cake"></i></span>
                                        <input type="text" class="form-control" name="joining_date" data-plugin-datepicker data-plugin-options='{ "todayHighlight" : true }' <?=$disabled?>
                                        autocomplete="off" value="<?=set_value('joining_date', $staff['joining_date'])?>">
                                    </div>
                                    <span class="error"><?php echo $this->validation->getError('joining_date'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('qualification')?> <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="qualification" <?=$disabled?> value="<?=set_value('qualification', $staff['qualification'])?>" />
                                    <span class="error"><?php echo $this->validation->getError('qualification'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('role')?> <span class="required">*</span></label>
                                    <?php
                                        $role_list = $this->app_lib->getRoles();
                                        echo form_dropdown("user_role", $role_list, set_value('user_role', $staff['role_id']), "class='form-control' disabled
                                        data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
                                    ?>
                                    <span class="error"><?php echo $this->validation->getError('user_role'); ?></span>
                                </div>
                            </div>
                        </div>
<?php } ?>
                        <!-- social links -->
                        <div class="headers-line">
                            <i class="fas fa-globe"></i> <?=translate('social_links')?>
                        </div>

                        <div class="row mb-md">
                            <div class="col-md-4 mb-sm">
                                <div class="form-group">
                                    <label class="control-label">Facebook</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fab fa-facebook-f"></i></span>
                                        <input type="text" class="form-control" name="facebook" value="<?=set_value('facebook', $staff['facebook_url'])?>" />
                                    </div>
                                    <span class="error"><?php echo $this->validation->getError('facebook'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-sm">
                                <div class="form-group">
                                    <label class="control-label">Twitter</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fab fa-twitter"></i></span>
                                        <input type="text" class="form-control" name="twitter" value="<?=set_value('twitter', $staff['twitter_url'])?>" />
                                    </div>
                                    <span class="error"><?php echo $this->validation->getError('twitter'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-sm">
                                <div class="form-group">
                                    <label class="control-label">Linkedin</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fab fa-linkedin-in"></i></span>
                                        <input type="text" class="form-control" name="linkedin" value="<?=set_value('linkedin', $staff['linkedin_url'])?>" />
                                    </div>
                                    <span class="error"><?php echo $this->validation->getError('linkedin'); ?></span>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-offset-9 col-md-3">
                            <button class="btn btn-default btn-block" type="submit"><i class="fas fa-plus-circle"></i> <?php echo translate('update'); ?></button>
                        </div>  
                    </div>
                </div>
            <?php echo form_close(); ?>
        </section>
    </div>
</div>
