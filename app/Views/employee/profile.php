<?php
use App\Libraries\App_lib;
use App\Models\EmployeeModel;

$this->db = \Config\Database::connect();
$this->app_lib = new App_lib();
$this->session = \Config\Services::session();
$this->validation = \Config\Services::validation();
$this->employee_model = new EmployeeModel();



?>





<?php $widget = (is_superadmin_loggedin() ? '4' : '6'); ?>
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
				<p><?php echo ucfirst($staff['role'])?> / <?php echo $staff['designation_name']; ?></p>
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
		<div class="panel-group" id="accordion">
			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
                        <div class="auth-pan">
                            <button class="btn btn-default btn-circle" id="authentication_btn">
                                <i class="fas fa-unlock-alt"></i> <?=translate('authentication')?>
                            </button>
                        </div>
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#profile">
							<i class="fas fa-user-edit"></i> <?=translate('basic_details')?>
						</a>
					</h4>
				</div>
				<div id="profile" class="accordion-body collapse <?=($this->session->getFlashdata('profile_tab') ? 'in' : ''); ?>">
					<?php echo form_open_multipart(uri_string()); ?>
						<div class="panel-body">
							<fieldset>
								<input type="hidden" name="staff_id" id="staff_id" value="<?php echo $staff['id']; ?>">
								<!-- academic details-->
								<div class="headers-line">
									<i class="fas fa-school"></i> <?=translate('academic_details')?>
								</div>
								<div class="row">
<?php if (is_superadmin_loggedin()) { ?>
									<div class="col-md-4 mb-sm">
										<div class="form-group">
											<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
											<?php
												$arrayBranch = $this->app_lib->getSelectList('branch');
												echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id', $staff['branch_id']), "class='form-control' id='branch_id'
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
												echo form_dropdown("user_role", $role_list, set_value('user_role', $staff['role_id']), "class='form-control'
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
												autocomplete="off" value="<?=set_value('joining_date', $staff['joining_date'])?>">
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
												$designation_list = $this->app_lib->getDesignation($staff['branch_id']);
												echo form_dropdown("designation_id", $designation_list, set_value('designation_id', $staff['designation']), "class='form-control' id='designation_id'
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
												echo form_dropdown("department_id", $department_list, set_value('department_id', $staff['department']), "class='form-control' id='department_id'
												data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
											?>
											<span class="error"><?php echo $this->validation->getError('department_id'); ?></span>
										</div>
									</div>
									<div class="col-md-4 mb-sm">
										<div class="form-group">
											<label class="control-label"><?=translate('qualification')?> <span class="required">*</span></label>
											<input type="text" class="form-control" name="qualification" value="<?=set_value('qualification', $staff['qualification'])?>" />
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
												<input class="form-control" name="name" type="text" value="<?=set_value('name', $staff['name'])?>" />
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

								<!--custom fields details-->
								<div class="row" id="customFields">
									<?php echo render_custom_Fields('employee', $staff['branch_id'], $staff['id']); ?>
								</div>
								
								<div class="row mb-md">
									<div class="col-md-12">
										<div class="form-group">
											<label for="input-file-now"><?=translate('profile_picture')?></label>
											<input type="file" name="user_photo" class="dropify" data-default-file="<?=get_image_url('staff', $staff['photo'])?>"/>
											<span class="error"><?php echo $this->validation->getError('user_photo'); ?></span>
										</div>
									</div>
									<input type="hidden" name="old_user_photo" value="<?=$staff['photo']?>">
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
												<input type="text" class="form-control" name="email" id="email" value="<?=set_value('email', $staff['email'])?>" />
											</div>
											<span class="error"><?php echo $this->validation->getError('email'); ?></span>
										</div>
									</div>
								</div>

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
									<button type="submit" name="submit" value="update" class="btn btn-default btn-block"><?=translate('update')?></button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			
			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#salary_transaction">
							<i class="far fa-address-card"></i> <?=translate('salary_transaction')?>
						</a>
					</h4>
				</div>
				<div id="salary_transaction" class="accordion-body collapse">
					<div class="panel-body">
                        <div class="table-responsive mb-sm mt-xs">
                            <table class="table table-bordered table-hover table-condensed mb-md mt-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?=translate('month_of_salary')?></th>
                                        <th><?=translate('basic_salary')?></th>
                                        <th><?=translate('allowances')?> (+)</th>
                                        <th><?=translate('deductions')?> (-)</th>
                                        <th><?=translate('paid_amount')?></th>
                                        <th><?=translate('payment_type')?></th>
                                        <th><?=translate('created_at')?></th>
                                        <th class="hidden-print"><?=translate('payslip')?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    $salaryresult = $this->db->table('payslip')->getWhere(array('staff_id' => $staff['id']))->getResultArray();
                                    if (count($salaryresult)) {
                                        foreach ($salaryresult as $row):
                                    ?>
                                    <tr>
                                        <td><?php echo $count++;?></td>
                                        <td><?php echo $this->app_lib->getMonthslist($row['month']) . " / " . $row['year']; ?></td>
                                        <td><?php echo $global_config['currency_symbol'] . $row['basic_salary']; ?></td>
                                        <td><?php echo $global_config['currency_symbol'] . $row['total_allowance']; ?></td>
                                        <td><?php echo $global_config['currency_symbol'] . $row['total_deduction']; ?></td>
                                        <td><?php echo $global_config['currency_symbol'] . $row['net_salary']; ?></td>
                                        <td><?php echo get_type_name_by_id('payment_types', $row['pay_via']); ?></td>
                                        <td><?php echo _d($row['created_at']);?></td>
                                        <td class="min-w-c hidden-print">
                                            <a href="<?php echo base_url('payroll/invoice/'.$row['id']."/".$row['hash']);?>" class="btn btn-default btn-circle"><i class="fas fa-eye"></i> <?=translate('view')?></a>
                                        </td>
                                    </tr>
                                    <?php 
                                        endforeach;
                                    }else{
                                        echo"<tr><td colspan='9'><h5 class='text-danger text-center'>". translate('no_information_available') ."</h5></td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
			</div>
	
			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#bank_account">
							<i class="fas fa-university"></i> <?=translate('bank_account')?>
						</a>
					</h4>
				</div>
				<div id="bank_account" class="accordion-body collapse <?=($this->session->getFlashdata('bank_tab') == 1 ? 'in' : ''); ?>">
					<div class="panel-body">
						<div class="text-right mb-sm">
							<a href="javascript:void(0);" onclick="mfp_modal('#addBankModal')" class="btn btn-circle btn-default mb-sm">
								<i class="fas fa-plus-circle"></i> <?=translate('add_bank')?>
							</a>
						</div>
						<div class="table-responsive mb-md">
							<table class="table table-bordered table-hover table-condensed mb-none">
							<thead>
								<tr>
									<th>#</th>
									<th><?=translate('bank_name')?></th>
									<th><?=translate('account_name')?></th>
									<th><?=translate('branch')?></th>
									<th><?=translate('bank_address')?></th>
									<th><?=translate('ifsc_code')?></th>
									<th><?=translate('account_no')?></th>
									<th><?=translate('actions')?></th>
								</tr>
							</thead>
							<tbody>
                                <?php
                                $count = 1;
                                $builder = $this->db->table('staff_bank_account')->where('staff_id', $staff['id']);
                                $bankResult = $builder->get()->getResultArray();
                                if (count($bankResult)) {
                                    foreach($bankResult as $bank):
                                    	?>
                                <tr>
                                    <td><?php echo $count++?></td>
                                    <td><?php echo $bank['bank_name']; ?></td>
                                    <td><?php echo $bank['holder_name']; ?></td>
                                    <td><?php echo $bank['bank_branch']; ?></td>
                                    <td><?php echo $bank['bank_address']; ?></td>
                                    <td><?php echo $bank['ifsc_code']; ?></td>
                                    <td><?php echo $bank['account_no']; ?></td>
                                    <td class="min-w-c">
                                        <a href="javascript:void(0);" onclick="editStaffBank('<?=$bank['id']?>')"  class="btn btn-circle icon btn-default">
                                            <i class="fas fa-pen-nib"></i>
                                        </a>
                                        <?php echo btn_delete('employee/bankaccount_delete/' . $bank['id']); ?>
                                    </td>
                                </tr>
                                <?php
                                    endforeach;
                                }else{
                                    echo '<tr> <td colspan="8"> <h5 class="text-danger text-center">' . translate('no_information_available') . '</h5> </td></tr>';
                                }
                                ?>
							</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			
			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#documents_details">
							<i class="fas fa-folder-open"></i> <?=translate('documents') . " " . translate('details')?>
						</a>
					</h4>
				</div>
				<div id="documents_details" class="accordion-body collapse <?=($this->session->getFlashdata('documents_details') == 1 ? 'in' : ''); ?>">
					<div class="panel-body">
						<div class="text-right mb-sm">
							<a href="javascript:void(0);" onclick="mfp_modal('#addStaffDocuments')" class="btn btn-circle btn-default mb-sm">
								<i class="fas fa-plus-circle"></i> <?=translate('add') . " " . translate('add_documents')?>
							</a>
						</div>
						<div class="table-responsive mb-md">
							<table class="table table-bordered table-hover table-condensed mb-none">
							<thead>
								<tr>
									<th>#</th>
									<th><?=translate('title')?></th>
									<th><?=translate('document_type')?></th>
									<th><?=translate('file')?></th>
									<th><?=translate('remarks')?></th>
									<th><?=translate('created_at')?></th>
									<th><?=translate('actions')?></th>
								</tr>
							</thead>
							<tbody>
                                <?php
                                $count = 1;
                                $builder = $this->db->table('staff_documents')->where('staff_id', $staff['id']);
                                $documents = $builder->get()->getResultArray();
                                if (count($documents)) {
                                    foreach($documents as $row):
                                ?>
                                <tr>
                                    <td><?php echo $count++?></td>
                                    <td><?php echo $row['title']; ?></td>
                                    <td><?php echo $categorylist[$row['category_id']]; ?></td>
                                    <td><?php echo $row['file_name']; ?></td>
                                    <td><?php echo $row['remarks']; ?></td>
                                    <td><?php echo _d($row['created_at']); ?></td>
                                    <td class="min-w-c">
                                        <a href="<?php echo base_url('employee/documents_download?file=' . $row['enc_name']); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?php echo translate('download'); ?>">
                                            <i class="fas fa-cloud-download-alt"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-circle icon btn-default" onclick="editDocument('<?=$row['id']?>', 'employee')">
                                            <i class="fas fa-pen-nib"></i>
                                        </a>
                                        <?php echo btn_delete('employee/document_delete/' . $row['id']); ?>
                                    </td>
                                </tr>
                                <?php
                                    endforeach;
                                }else{
                                    echo '<tr> <td colspan="7"> <h5 class="text-danger text-center">' . translate('no_information_available') . '</h5> </td></tr>';
                                }
                                ?>
							</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
<?php if($staff['role_id'] == 3): ?>
			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#class_schedule">
							<i class="fas fa-dna"></i> <?=translate('class_schedule')?>
						</a>
					</h4>
				</div>
				<div id="class_schedule" class="accordion-body collapse">
                    <div class="panel-body">
                        <div class="table-responsive mb-sm mt-xs">
                            <table class="table table-bordered table-hover table-condensed mb-md mt-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?=translate('subject')?></th>
                                        <th><?=translate('class')?></th>
                                        <th><?=translate('section')?></th>
                                        <th><?=translate('class_room')?></th>
                                        <th><?=translate('time_start')?></th>
                                        <th><?=translate('time_end')?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    $schedules = $this->employee_model->get_schedule_by_id($staff['id']);
                                    if ($schedules->getNumRows() > 0) {
                                        $schedules = $schedules->getResult();
                                        foreach ($schedules as $row):
                                    ?>
                                    <tr>
                                        <td><?php echo $count++;?></td>
                                        <td><?php echo $row->subject_name;?></td>
                                        <td><?php echo $row->class_name;?></td>
                                        <td><?php echo $row->section_name;?></td>
                                        <td><?php echo (empty($row->class_room) ? "N/A" : $row->class_room);?></td>
                                        <td><?php echo date("g:i A", strtotime($row->time_start));?></td>
                                        <td><?php echo date("g:i A", strtotime($row->time_end));?></td>
                                        
                                    </tr>
                                    <?php endforeach;?>
                                    <?php 
                                    }else{
                                        echo"<tr><td colspan='7'><h5 class='text-danger text-center'>". translate('no_information_available') ."</h5></td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
				</div>
			</div>
<?php endif; ?>
		</div>
	</div>
</div>

<!-- Documents Details Add Modal -->
<div id="addStaffDocuments" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
    <section class="panel">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-plus-circle"></i> <?php echo translate('add') . " " . translate('document'); ?></h4>
        </div>
		<?php echo form_open_multipart('employee/document_create', array('class' => 'form-horizontal frm-submit-data')); ?>
            <div class="panel-body">
                <input type="hidden" name="staff_id" value="<?php echo esc($staff['id']); ?>">
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?php echo translate('title'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="document_title" id="adocument_title" value="" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo translate('category'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <?php
                        echo form_dropdown("document_category", $categorylist, set_value('document_category'), "class='form-control' data-plugin-selectTwo
                        data-width='100%' id='adocument_category' data-minimum-results-for-search='Infinity' ");
                        ?>
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo translate('document') . " " . translate('file'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="file" name="document_file" class="dropify" data-height="110" data-default-file="" id="adocument_file" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mb-md">
                    <label class="col-md-3 control-label"><?php echo translate('remarks'); ?></label>
                    <div class="col-md-9">
                        <textarea class="form-control valid" rows="2" name="remarks"></textarea>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" id="docsavebtn" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                            <i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
                        </button>
                        <button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
                    </div>
                </div>
			</footer>
        <?php echo form_close(); ?>
    </section>
</div>

<!-- Documents Details Edit Modal -->
<div id="editDocModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
    <section class="panel">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('document'); ?></h4>
        </div>
		<?php echo form_open_multipart('employee/document_update', array('class' => 'form-horizontal frm-submit-data')); ?>
            <div class="panel-body">
                <input type="hidden" name="document_id" id="edocument_id" value="">
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?php echo translate('title'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="document_title" id="edocument_title" value="" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo translate('category'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <?php
                            echo form_dropdown("document_category", $categorylist, set_value('document_category'), "class='form-control' data-plugin-selectTwo id='edocument_category'
                            data-width='100%' data-minimum-results-for-search='Infinity' ");
                        ?>
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo translate('document') . " " . translate('file'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="file" name="document_file" class="dropify" data-height="120" data-default-file="">
                        <input type="hidden" name="exist_file_name" id="exist_file_name" value="">
                    </div>
                </div>
                <div class="form-group mb-md">
                    <label class="col-md-3 control-label"><?php echo translate('remarks'); ?></label>
                    <div class="col-md-9">
                        <textarea class="form-control valid" rows="2" name="remarks" id="edocuments_remarks"></textarea>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-default" id="doceditbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                            <?php echo translate('update'); ?>
                        </button>
                        <button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
                    </div>
                </div>
			</footer>
        <?php echo form_close(); ?>
    </section>
</div>

<!-- Bank Details Add Modal -->
<div id="addBankModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
    <section class="panel">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-plus-circle"></i> <?php echo translate('add') . " " . translate('bank'); ?></h4>
        </div>
        <?php echo form_open('employee/bank_account_create', array('class' => 'form-horizontal frm-submit')); ?>
            <div class="panel-body">
                <input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('bank') . " " . translate('name'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="bank_name" id="abank_name" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('holder_name'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="account_name" id="aholder_name" />
                         <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('bank') . " " . translate('branch'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="bank_branch" id="abank_branch" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('ifsc_code'); ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="ifsc_code" id="aifsc_code" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('account_no'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="account_no" id="aaccount_no" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mb-md">
                    <label class="col-md-3 control-label"><?php echo translate('bank') . " " . translate('address'); ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="bank_address" id="abank_address" />
                        <span class="error"></span>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-default" id="bankaddbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                            <i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
                        </button>
                        <button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
                    </div>
                </div>
            </footer>
        <?php echo form_close(); ?>
    </section>
</div>

<!-- Bank Details Edit Modal -->
<div id="editBankModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
    <section class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('bank_account'); ?></h4>
        </header>
        <?php echo form_open('employee/bank_account_update', array('class' => 'form-horizontal frm-submit')); ?>
            <div class="panel-body">
                <input type="hidden" name="bank_id" id="ebank_id" value="">
                <input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('bank') . " " . translate('name'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="bank_name" id="ebank_name" value="" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('holder_name'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="account_name" id="eholder_name" value="" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('bank') . " " . translate('branch'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="bank_branch" id="ebank_branch" value="" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('ifsc_code'); ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="ifsc_code" id="eifsc_code" value="" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('account_no'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="account_no" id="eaccount_no" value="" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mb-md">
                    <label class="col-md-3 control-label"><?php echo translate('bank') . " " . translate('address'); ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="bank_address" id="ebank_address" value="" />
                        <span class="error"></span>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-default" id="bankeditbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                            <?php echo translate('update'); ?>
                        </button>
                        <button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
                    </div>
                </div>
            </footer>
        <?php echo form_close(); ?>
    </section>
</div>


<!-- login authentication and account inactive modal -->
<div id="authentication_modal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title">
				<i class="fas fa-unlock-alt"></i> <?=translate('authentication')?>
			</h4>
		</header>
		<?php echo form_open('employee/change_password', array('class' => 'frm-submit')); ?>
        <div class="panel-body">
        	<input type="hidden" name="staff_id" value="<?=$staff['id']?>">
            <div class="form-group">
	            <label for="password" class="control-label"><?=translate('password')?> <span class="required">*</span></label>
	            <div class="input-group">
	                <input type="password" class="form-control password" name="password" autocomplete="off" />
	                <span class="input-group-addon">
	                    <a href="javascript:void(0);" id="showPassword" ><i class="fas fa-eye"></i></a>
	                </span>
	            </div>
	            <span class="error"></span>
                <div class="checkbox-replace mt-lg">
                    <label class="i-checks">
                        <input type="checkbox" name="authentication" id="cb_authentication">
                        <i></i> <?=translate('login_authentication_deactivate')?>
                    </label>
                </div>
            </div>
        </div>
        <footer class="panel-footer">
            <div class="text-right">
                <button type="submit" class="btn btn-default mr-xs" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing"><?=translate('update')?></button>
                <button class="btn btn-default modal-dismiss"><?=translate('close')?></button>
            </div>
        </footer>
        <?php echo form_close(); ?>
	</section>
</div>

<script type="text/javascript">
	var authenStatus = "<?=$staff['active']?>";
</script>
