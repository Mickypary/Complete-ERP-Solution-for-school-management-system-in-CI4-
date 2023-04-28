<?php

use App\Libraries\App_lib;
$this->app_lib = new App_lib();

$this->validation = \Config\Services::validation();

?>




<?php $widget = (is_superadmin_loggedin() ? '4' : '6'); ?>
<div class="row">
	<div class="col-md-12">
		<section class="panel">
				<div class="panel-heading">
                    <div class="panel-btn">
						<a href="javascript:void(0);" onclick="mfp_modal('#multipleImport')" class="btn btn-circle btn-default mb-sm">
							<i class="fas fa-plus-circle"></i> <?=translate('multiple_import')?>
						</a>
                    </div>
					<h4 class="panel-title">
						<i class="far fa-user-circle"></i> <?=translate('add_employee')?>
					</h4>
				</div>
			<?php echo form_open_multipart(uri_string()); ?>
				
				<div class="panel-body">
					<!-- academic details-->
					<div class="headers-line mt-md">
						<i class="fas fa-school"></i> <?=translate('academic_details')?>
					</div>
					<div class="row">
<?php if (is_superadmin_loggedin()) { ?>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
								<?php
									$arrayBranch = $this->app_lib->getSelectList('branch');
									echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id'
									data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"><?php echo $this->validation->getError('branch_id'); ?></span>
							</div>
						</div>
<?php } ?>
						<div class="col-md-<?=$widget?> mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('role')?> <span class="required">*</span></label>
								<?php
									$role_list = $this->app_lib->getRoles();
									echo form_dropdown("user_role", $role_list, set_value('user_role'), "class='form-control'
									data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
								?>
								<span class="error"><?php echo $this->validation->getError('user_role'); ?></span>
							</div>
						</div>
						<div class="col-md-<?=$widget?> mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('joining_date')?> <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-birthday-cake"></i></span>
									<input type="text" class="form-control" name="joining_date" data-plugin-datepicker data-plugin-options='{ "todayHighlight" : true }'
									autocomplete="off" value="<?=set_value('joining_date')?>" />
								</div>
								<span class="error"><?php echo $this->validation->getError('joining_date'); ?></span>
							</div>
						</div>
					</div>

					<div class="row mb-lg">
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('designation')?> <span class="required">*</span></label>
								<?php
									$department_list = $this->app_lib->getDesignation($branch_id);
									echo form_dropdown("designation_id", $department_list, set_value('designation_id'), "class='form-control' id='designation_id'
									data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"><?php echo $this->validation->getError('designation_id'); ?></span>
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('department')?> <span class="required">*</span></label>
								<?php
									$department_list = $this->app_lib->getDepartment($branch_id);
									echo form_dropdown("department_id", $department_list, set_value('department_id'), "class='form-control' id='department_id'
									data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"><?php echo $this->validation->getError('department_id'); ?></span>
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('qualification')?> <span class="required">*</span></label>
								<input type="text" class="form-control" name="qualification" value="<?=set_value('qualification')?>">
								<span class="error"><?php echo $this->validation->getError('qualification'); ?></span>
							</div>
						</div>
					</div>

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
									<input type="text" class="form-control" name="name" value="<?=set_value('name')?>" autocomplete="off" />
								</div>
								<span class="error"><?php echo $this->validation->getError('name'); ?></span>
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
									echo form_dropdown("sex", $array, set_value('sex'), "class='form-control' data-plugin-selectTwo data-width='100%'
									data-minimum-results-for-search='Infinity'");
								?>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('religion')?></label>
								<input type="text" class="form-control" name="religion" value="<?=set_value('religion')?>">
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('blood_group')?></label>
								<?php
									$bloodArray = $this->app_lib->getBloodgroup();
									echo form_dropdown("blood_group", $bloodArray, set_value("blood_group"), "class='form-control populate' data-plugin-selectTwo
									data-width='100%' data-minimum-results-for-search='Infinity' ");
								?>
							</div>
						</div>

						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('birthday')?> </label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-birthday-cake"></i></span>
									<input class="form-control" name="birthday" autocomplete="off" value="<?=set_value('birthday')?>" data-plugin-datepicker
									data-plugin-options='{ "startView": 2 }' type="text">
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
									<input class="form-control" name="mobile_no" type="text" value="<?=set_value('mobile_no')?>" autocomplete="off" />
								</div>
								<span class="error"><?php echo $this->validation->getError('mobile_no'); ?></span>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('present_address')?> <span class="required">*</span></label>
								<textarea class="form-control" rows="2" name="present_address" placeholder="<?=translate('present_address')?>" ><?=set_value('present_address')?></textarea>
							</div>
							<span class="error"><?php echo $this->validation->getError('present_address'); ?></span>
						</div>
						<div class="col-md-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('permanent_address')?></label>
								<textarea class="form-control" rows="2" name="permanent_address" placeholder="<?=translate('permanent_address')?>" ><?=set_value('permanent_address')?></textarea>
							</div>
						</div>
					</div>

					<!--custom fields details-->
					<div class="row" id="customFields">
						<?php echo render_custom_Fields('employee'); ?>
					</div>
					
					<div class="row mb-md">
						<div class="col-md-12">
							<div class="form-group">
								<label for="input-file-now"><?=translate('profile_picture')?></label>
								<input type="file" name="user_photo" class="dropify" />
								<span class="error"><?php echo $this->validation->getError('user_photo'); ?></span>
							</div>
						</div>
					</div>

					<!-- login details -->
					<div class="headers-line">
						<i class="fas fa-user-lock"></i> <?=translate('login_details')?>
					</div>

					<div class="row mb-lg">
						<div class="col-md-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('email')?> <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="far fa-envelope-open"></i></span>
									<input type="text" class="form-control" name="email" id="email" value="<?=set_value('email')?>" autocomplete="off" />
								</div>
								<span class="error"><?php echo $this->validation->getError('email'); ?></span>
							</div>
						</div>
						<div class="col-md-3 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('password')?> <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-unlock-alt"></i></span>
									<input type="password" class="form-control" name="password" value="<?=set_value('password')?>" />
								</div>
								<span class="error"><?php echo $this->validation->getError('password'); ?></span>
							</div>
						</div>
						<div class="col-md-3 mb-sm">
							<div class="form-group">
								<label class="control-label"><?=translate('retype_password')?> <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-unlock-alt"></i></span>
									<input type="password" class="form-control" name="retype_password" value="<?=set_value('retype_password')?>" />
								</div>
								<span class="error"><?php echo $this->validation->getError('retype_password'); ?></span>
							</div>
						</div>
					</div>

					<!-- social links -->
					<div class="headers-line">
						<i class="fas fa-globe"></i> <?=translate('social_links')?>
					</div>

					<div class="row mb-lg">
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label">Facebook</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fab fa-facebook-f"></i></span>
									<input type="text" class="form-control" name="facebook" value="<?=set_value('facebook')?>" placeholder="eg: https://www.facebook.com/username" />
								</div>
								<span class="error"><?php echo $this->validation->getError('facebook'); ?></span>
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label">Twitter</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fab fa-twitter"></i></span>
									<input type="text" class="form-control" name="twitter" value="<?=set_value('twitter')?>" placeholder="eg: https://www.twitter.com/username" />
								</div>
								<span class="error"><?php echo $this->validation->getError('twitter'); ?></span>
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label">Linkedin</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fab fa-linkedin-in"></i></span>
									<input type="text" class="form-control" name="linkedin" value="<?=set_value('linkedin')?>" placeholder="eg: https://www.linkedin.com/username" />
								</div>
								<span class="error"><?php echo $this->validation->getError('linkedin'); ?></span>
							</div>
						</div>
					</div>

					<!-- bank details -->
					<div class="headers-line">
						<i class="fas fa-university"></i> <?=translate('bank_details')?>
					</div>
					<div class="mb-sm checkbox-replace">
						<label class="i-checks"><input type="checkbox" name="chkskipped" id="chk_bank_skipped" value="true" <?=set_checkbox('chkskipped', 'true')?> >
							<i></i> <?=translate('skipped_bank_details')?>
						</label>
					</div>
					<div id="bank_details_form" <?php if(!empty(set_value('chkskipped'))) { ?> style="display: none" <?php } ?>>
						<div class="row">
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('bank_name')?> <span class="required">*</span></label>
									<input type="text" class="form-control" name="bank_name" value="<?=set_value('bank_name')?>" />
								</div>
								<span class="error"><?php echo $this->validation->getError('bank_name'); ?></span>
							</div>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('account_name')?> <span class="required">*</span></label>
									<input type="text" class="form-control" name="account_name" value="<?=set_value('account_name')?>" />
									<span class="error"><?php echo $this->validation->getError('account_name'); ?></span>
								</div>
							</div>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('bank') . ' ' . translate('branch')?> <span class="required">*</span></label>
									<input type="text" class="form-control" name="bank_branch" value="<?=set_value('bank_branch')?>" />
									<span class="error"><?php echo $this->validation->getError('bank_branch'); ?></span>
								</div>
							</div>
						</div>

						<div class="row mb-lg">
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('bank_address')?></label>
									<input type="text" class="form-control" name="bank_address" value="<?=set_value('bank_address')?>" />
								</div>
							</div>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('ifsc_code')?></label>
									<input type="text" class="form-control" name="ifsc_code" value="<?=set_value('ifsc_code')?>" />
								</div>
							</div>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('account_no')?> <span class="required">*</span></label>
									<input type="text" class="form-control" name="account_no" value="<?=set_value('account_no')?>" />
									<span class="error"><?php echo $this->validation->getError('account_no'); ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-offset-10 col-md-2">
							<button type="submit" name="submit" value="save" class="btn btn btn-default btn-block"> <i class="fas fa-plus-circle"></i> <?=translate('save')?></button>
						</div>
					</div>
				</footer>
			<?php echo form_close();?>
		</section>
	</div>
</div>

<!-- multiple import modal -->
<div id="multipleImport" class="zoom-anim-dialog modal-block modal-block-lg mfp-hide">
    <section class="panel">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-plus-circle"></i> <?php echo translate('multiple_import'); ?></h4>
        </div>
        <?php echo form_open_multipart('employee/csv_import', array('class' => 'form-horizontal', 'id' => 'importCSV')); ?>
            <div class="panel-body">
            	<div class="alert-danger" id="errorList" style="display: none; padding: 8px;">rthtrhtr</div>
				<div class="form-group mt-md">
					<div class="col-md-12 mb-md">
						<a class="btn btn-default pull-right" href="<?=base_url('employee/csv_Sampledownloader')?>">
							<i class='fas fa-file-download'></i> Download Sample Import File
						</a>
					</div>
					<div class="col-md-12">
						<div class="alert alert-subl">
							<strong>Instructions :</strong><br/>
							1. Download the first sample file.<br/>
							2. Open the downloaded "CSV" file and carefully fill in the employee details.<br/>
							3. The date you are trying to enter the "Date Of Birth" and "Joining Date" column make sure the date format is Y-m-d (<?=date('Y-m-d')?>).<br/>
						</div>
					</div>
				</div>
<?php if (is_superadmin_loggedin()) { ?>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('branch')?> <span class="required">*</span></label>
					<div class="col-md-9">
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branchID_mod'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"></span>
					</div>
				</div>
<?php } ?>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('role')?> <span class="required">*</span></label>
					<div class="col-md-9">
						<?php
							$role_list = $this->app_lib->getRoles();
							echo form_dropdown("user_role", $role_list, set_value('user_role'), "class='form-control'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
						?>
						<span class="error"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('designation')?> <span class="required">*</span></label>
					<div class="col-md-9">
						<?php
							$department_list = $this->app_lib->getDesignation($branch_id);
							echo form_dropdown("designation_id", $department_list, set_value('designation_id'), "class='form-control' id='designationID_mod'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('department')?> <span class="required">*</span></label>
					<div class="col-md-9">
						<?php
							$department_list = $this->app_lib->getDepartment($branch_id);
							echo form_dropdown("department_id", $department_list, set_value('department_id'), "class='form-control' id='departmentID_mod'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"></span>
					</div>
				</div>
				<div class="form-group mb-xs">
					<label class="control-label col-md-3">Select CSV File <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="file" name="userfile" class="dropify" data-height="70" data-allowed-file-extensions="csv" />
						<span class="error"></span>
					</div>
				</div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-default mr-xs" id="bankaddbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                            <i class="fas fa-plus-circle"></i> <?php echo translate('import'); ?>
                        </button>
                        <button class="btn btn-default modal-dismiss"><?php echo translate('close'); ?></button>
                    </div>
                </div>
            </footer>
        <?php echo form_close(); ?>
    </section>
</div>