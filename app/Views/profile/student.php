<?php

use App\Models\StudentModel;
use App\Libraries\App_lib;

$this->student_model = new StudentModel();
$this->validation = \Config\Services::validation();
$this->app_lib = new App_lib();
$this->db = \Config\Database::connect();


$widget = (is_superadmin_loggedin() ? 3 : 4);
$getParent = $this->student_model->where('id', $student['parent_id'])->findAll();
// $getParent = $this->student_model->findAll();
$branchID = $student['branch_id'];
$previous_details = json_decode($student['previous_details'], true);
?>
<div class="row appear-animation" data-appear-animation="<?=$global_config['animations'] ?>">
	<div class="col-md-12 mb-lg">
		<div class="profile-head">
			<div class="col-md-12 col-lg-4 col-xl-3">
				<div class="image-content-center user-pro">
					<div class="preview">
						<img src="<?php echo get_image_url('student', $student['photo']);?>">
					</div>
				</div>
			</div>
			<div class="col-md-12 col-lg-5 col-xl-5">
				<h5><?=$student['first_name'] . ' ' . $student['last_name']?></h5>
				<p><?=translate('student')  . " / " . $student['category_name']?></p>
				<ul>
					<li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?=translate('guardian_name')?>"><i class="fas fa-users"></i></div> <?=(!empty($getParent['name']) ? $getParent['name'] : 'N/A'); ?></li>
					<li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?=translate('birthday')?>"><i class="fas fa-birthday-cake"></i></div> <?=_d($student['birthday'])?></li>
					<li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?=translate('class')?>"><i class="fas fa-school"></i></div> <?=$student['class_name'] . ' ('.$student['section_name'] . ')'?></li>
					<li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?=translate('mobile_no')?>"><i class="fas fa-phone-volume"></i></div> <?=(!empty($student['mobileno']) ? $student['mobileno'] : 'N/A'); ?></li>
					<li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?=translate('email')?>"><i class="far fa-envelope"></i></div> <?=$student['email']?></li>
					<li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?=translate('present_address')?>"><i class="fas fa-home"></i></div> <?=(!empty($student['current_address']) ? $student['current_address'] : 'N/A'); ?></li>
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
						<input type="hidden" name="student_id" value="<?=$student['category_name']?>">
						<!-- academic details-->
						<div class="headers-line">
							<i class="fas fa-school"></i> <?=translate('academic_details')?>
						</div>
						<div class="row">
							<div class="col-md-3 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('academic_year')?> <span class="required">*</span></label>
									<?php
										$arrayYear = array("" => translate('select'));
										$years = $this->db->table('schoolyear')->get()->getResult();
										foreach ($years as $year){
											$arrayYear[$year->id] = $year->school_year;
										}
										echo form_dropdown("year_id", $arrayYear, set_value('year_id', $student['session_id']), "class='form-control' id='academic_year_id' disabled
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
									<span class="error"><?=$this->validation->getError('year_id')?></span>
								</div>
							</div>

							<div class="col-md-3 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('register_no')?> <span class="required">*</span></label>
									<input type="text" class="form-control" name="register_no" disabled value="<?=set_value('register_no', $student['register_no'])?>" />
									<span class="error"><?=$this->validation->getError('register_no')?></span>
								</div>
							</div>

							<div class="col-md-3 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('roll')?> <span class="required">*</span></label>
									<input type="text" class="form-control" name="roll" disabled value="<?=set_value('roll', $student['roll'])?>" />
									<span class="error"><?=$this->validation->getError('roll')?></span>
								</div>
							</div>
							<div class="col-md-3 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('admission_date')?> <span class="required">*</span></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="far fa-calendar-alt"></i></span>
										<input type="text" class="form-control" name="admission_date" disabled
										value="<?=set_value('admission_date', $student['admission_date'])?>" data-plugin-datepicker data-plugin-options='{ "todayHighlight" : true }' />
									</div>
									<span class="error"><?=$this->validation->getError('admission_date')?></span>
								</div>
							</div>
						</div>

						<div class="row mb-md">
							<?php if (is_superadmin_loggedin()): ?>
							<div class="col-md-<?php echo $widget; ?> mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
									<?php
										$arrayBranch = $this->app_lib->getSelectList('branch');
										echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id', $branchID), "class='form-control' id='branch_id' disabled
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
									?>
									<span class="error"><?=$this->validation->getError('branch_id')?></span>
								</div>
							</div>
							<?php endif; ?>
							<div class="col-md-<?php echo $widget; ?> mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
									<?php
										$arrayClass = $this->app_lib->getClass($branchID);
										echo form_dropdown("class_id", $arrayClass, set_value('class_id', $student['class_id']), "class='form-control' id='class_id' disabled
										onchange='getSectionByClass(this.value,0)' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
									<span class="error"><?=$this->validation->getError('class_id')?></span>
								</div>
							</div>
							<div class="col-md-<?php echo $widget; ?> mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
									<?php
										$arraySection = $this->app_lib->getSections(set_value('class_id', $student['class_id']), true);
										echo form_dropdown("section_id", $arraySection, set_value('section_id', $student['section_id']), "class='form-control' id='section_id' disabled
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
									<span class="error"><?=$this->validation->getError('section_id')?></span>
								</div>
							</div>
							<div class="col-md-<?php echo $widget; ?> mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('category')?> <span class="required">*</span></label>
									<?php
										$arrayCategory = $this->app_lib->getStudentCategory($branchID);
										echo form_dropdown("category_id", $arrayCategory, set_value('category_id', $student['category_id']), "class='form-control' disabled
										data-plugin-selectTwo data-width='100%' id='category_id' data-minimum-results-for-search='Infinity' ");
									?>
									<span class="error"><?=$this->validation->getError('category_id')?></span>
								</div>
							</div>
						</div>
						
						<!-- student details -->
						<div class="headers-line mt-md">
							<i class="fas fa-user-check"></i> <?=translate('student_details')?>
						</div>
						
						<div class="row">
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('first_name')?> <span class="required">*</span></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-user-graduate"></i></span>
										<input type="text" class="form-control" name="first_name" value="<?=set_value('first_name', $student['first_name'])?>"/>
										<span class="error"><?=$this->validation->getError('first_name')?></span>
									</div>
								</div>
							</div>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('last_name')?> <span class="required">*</span> </label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-user-graduate"></i></span>
										<input type="text" class="form-control" name="last_name" value="<?=set_value('last_name', $student['last_name'])?>" />
										<span class="error"><?=$this->validation->getError('last_name')?></span>
									</div>
								</div>
							</div>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('gender')?> </label>
									<?php
										$arrayGender = array(
											'male' => translate('male'),
											'female' => translate('female')
										);
										echo form_dropdown("gender", $arrayGender, set_value('gender', $student['gender']), "class='form-control'
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('blood_group')?></label>
									<?php
										$bloodArray = $this->app_lib->getBloodgroup();
										echo form_dropdown("blood_group", $bloodArray, set_value("blood_group", $student['blood_group']), "class='form-control populate' data-plugin-selectTwo 
										data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
								</div>
							</div>
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('birthday')?></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-birthday-cake"></i></span>
										<input type="text" class="form-control" name="birthday" value="<?=set_value('birthday', $student['birthday'])?>" data-plugin-datepicker
										data-plugin-options='{ "startView": 2 }' />
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('mother_tongue')?></label>
									<input type="text" class="form-control" name="mother_tongue" value="<?=set_value('mother_tongue', $student['mother_tongue'])?>" />
								</div>
							</div>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('religion')?></label>
									<input type="text" class="form-control" name="religion" value="<?=set_value('religion', $student['religion'])?>" />
								</div>
							</div>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('caste')?></label>
									<input type="text" class="form-control" name="caste" value="<?=set_value('caste', $student['caste'])?>" />
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('mobile_no')?> <span class="required">*</span></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fas fa-phone-volume"></i></span>
										<input type="text" class="form-control" name="mobileno" value="<?=set_value('mobileno', $student['mobileno'])?>" />
									</div>
									<span class="error"><?=$this->validation->getError('mobileno')?></span>
								</div>
							</div>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('city')?></label>
									<input type="text" class="form-control" name="city" value="<?=set_value('city', $student['city'])?>" />
								</div>
							</div>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('state')?></label>
									<input type="text" class="form-control" name="state" value="<?=set_value('state', $student['state'])?>" />
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('present_address')?></label>
									<textarea name="current_address" rows="2" class="form-control" aria-required="true"><?=set_value('current_address', $student['current_address'])?></textarea>
								</div>
							</div>
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('permanent_address')?></label>
									<textarea name="permanent_address" rows="2" class="form-control" aria-required="true"><?=set_value('permanent_address', $student['permanent_address'])?></textarea>
								</div>
							</div>
						</div>
						
						<div class="row mb-md">
							<div class="col-md-12">
								<div class="form-group">
									<label for="input-file-now"><?=translate('profile_picture')?></label>
									<input type="file" name="user_photo" class="dropify" data-default-file="<?=get_image_url('student', $student['photo'])?>" />
									<input type="hidden" name="old_user_photo" value="<?php echo $student['photo']; ?>" />
								</div>
								<span class="error"><?=$this->validation->getError('user_photo')?></span>
							</div>
						</div>

						<!-- login details -->
						<div class="headers-line">
							<i class="fas fa-user-lock"></i> <?=translate('login_details')?>
						</div>

						<div class="row mb-md">
							<div class="col-md-12 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('email')?> <span class="required">*</span></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="far fa-envelope-open"></i></span>
										<input type="text" class="form-control" name="email" id="email" value="<?=set_value('email', $student['email'])?>" />
									</div>
									<span class="error"><?=$this->validation->getError('email')?></span>
								</div>
							</div>
						</div>

						<!--guardian details-->
						<div class="headers-line">
							<i class="fas fa-user-tie"></i> <?=translate('guardian_details')?>
						</div>
						<div class="row mb-md">
							<div class="col-md-12 mb-md">
								<label class="control-label"><?=translate('guardian')?> <span class="required">*</span></label>
								<div class="form-group">
									<?php
										$arrayParent = $this->app_lib->getSelectByBranch('parent', $branchID);
										echo form_dropdown("parent_id", $arrayParent, set_value('parent_id', $student['parent_id']), "class='form-control' id='parent_id' disabled
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
									<span class="error"><?=$this->validation->getError('parent_id')?></span>
								</div>
							</div>
						</div>

						<!-- transport details -->
						<div class="headers-line">
							<i class="fas fa-bus-alt"></i> <?=translate('transport_details')?>
						</div>

						<div class="row mb-md">
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('transport_route')?></label>
									<?php
										$arrayRoute = $this->app_lib->getSelectByBranch('transport_route', $branchID);
										echo form_dropdown("route_id", $arrayRoute, set_value('route_id', $student['route_id']), "class='form-control' disabled
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
								</div>
							</div>
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('vehicle_no')?></label>
									<?php
										$arrayVehicle = $this->app_lib->getVehicleByRoute(set_value('route_id', $student['route_id']));
										echo form_dropdown("vehicle_id", $arrayVehicle, set_value('vehicle_id', $student['vehicle_id']), "class='form-control' disabled
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
								</div>
							</div>
						</div>

						<!-- hostel details -->
						<div class="headers-line">
							<i class="fas fa-hotel"></i> <?=translate('hostel_details')?>
						</div>

						<div class="row mb-md">
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('hostel_name')?></label>
									<?php
										$arrayHostel = $this->app_lib->getSelectByBranch('hostel', $branchID);
										echo form_dropdown("hostel_id", $arrayHostel, set_value('hostel_id', $student['hostel_id']), "class='form-control' disabled
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
								</div>
							</div>
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('room_name')?></label>
									<?php
										$arrayRoom = $this->app_lib->getRoomByHostel(set_value('hostel_id', $student['hostel_id']));
										echo form_dropdown("room_id", $arrayRoom, set_value('room_id', $student['room_id']), "class='form-control' disabled
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
								</div>
							</div>
						</div>

						<!-- previous school details -->
						<div class="headers-line">
							<i class="fas fa-bezier-curve"></i> <?=translate('previous_school_details')?>
						</div>
						<div class="row">
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('school_name')?></label>
									<input type="text" class="form-control" name="school_name" disabled value="<?=$previous_details['school_name']?>" />
								</div>
							</div>
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('qualification')?></label>
									<input type="text" class="form-control" name="qualification" disabled value="<?=$previous_details['qualification']?>" />
								</div>
							</div>
						</div>
						<div class="row mb-lg">
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label"><?=translate('remarks')?></label>
									<textarea name="previous_remarks" rows="2" disabled class="form-control"><?=$previous_details['remarks']?></textarea>
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
