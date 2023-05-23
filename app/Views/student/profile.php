<?php
use App\Models\ApplicationModel;
use App\Models\ExamModel;
use App\Models\FeesModel;
use App\Models\StudentModel;
use App\Libraries\App_lib;

$this->db = \Config\Database::connect();
$this->validation = \Config\Services::validation();
$this->application_model = new ApplicationModel();
$this->exam_model = new ExamModel();
$this->fees_model = new FeesModel();
$this->student_model = new StudentModel();
$this->session = \Config\Services::session();
$this->app_lib = new App_lib();

$currency_symbol = $global_config['currency_symbol'];
$widget = (is_superadmin_loggedin() ? 3 : 4);
$branchID = $student['branch_id'];
$getParent = $this->student_model->where('id', $student['parent_id'])->findAll();
// print_r($getParent);
// die();
$previous_details = json_decode($student['previous_details'], true);
?>
<div class="row appear-animation" data-appear-animation="<?=$global_config['animations'] ?>" data-appear-animation-delay="100">
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
				<p>Student / <?=$student['category_name']?></p>
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
		<div class="panel-group" id="accordion">
            <!-- student profile information user Interface -->
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
				<div id="profile" class="accordion-body collapse <?=($this->session->getFlashdata('profile_tab') == 1 ? 'in' : ''); ?>">
					<?php echo form_open_multipart(uri_string()); ?>
					<input type="hidden" name="student_id" value="<?php echo $student['id']; ?>" id="student_id">
					<div class="panel-body">
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
										echo form_dropdown("year_id", $arrayYear, set_value('year_id', $student['session_id']), "class='form-control' id='academic_year_id'
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
									<span class="error"><?=$this->validation->getError('year_id')?></span>
								</div>
							</div>

							<div class="col-md-3 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('register_no')?> <span class="required">*</span></label>
									<input type="text" class="form-control" name="register_no" value="<?=set_value('register_no', $student['register_no'])?>" />
									<span class="error"><?=$this->validation->getError('register_no')?></span>
								</div>
							</div>

							<div class="col-md-3 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('roll')?> <span class="required">*</span></label>
									<input type="text" class="form-control" name="roll" value="<?=set_value('roll', $student['roll'])?>" />
									<span class="error"><?=$this->validation->getError('roll')?></span>
								</div>
							</div>
							<div class="col-md-3 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('admission_date')?> <span class="required">*</span></label>
									<div class="input-group">
										<span class="input-group-addon"><i class="far fa-calendar-alt"></i></span>
										<input type="text" class="form-control" name="admission_date"
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
										echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id', $student['branch_id']), "class='form-control' id='branch_id'
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
										echo form_dropdown("class_id", $arrayClass, set_value('class_id', $student['class_id']), "class='form-control' id='class_id' 
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
										echo form_dropdown("section_id", $arraySection, set_value('section_id', $student['section_id']), "class='form-control' id='section_id'
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
										echo form_dropdown("category_id", $arrayCategory, set_value('category_id', $student['category_id']), "class='form-control'
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

						<!--custom fields details-->
						<div class="row" id="customFields">
							<?php echo render_custom_Fields('student', $student['branch_id'], $student['id']); ?>
						</div>
						
						<div class="row">
							<div class="col-md-12 mb-sm">
								<div class="form-group">
									<label for="input-file-now"><?=translate('profile_picture')?></label>
									<input type="file" name="user_photo" class="dropify" data-default-file="<?=get_image_url('student', $student['photo'])?>" />
									<input type="hidden" name="old_user_photo" value="<?php echo $student['photo']; ?>" />
								</div>
								<span class="error"><?=$this->validation->getError('user_photo')?></span>
							</div>
						</div>

						<!-- login details -->
						<div class="headers-line mt-md">
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
										echo form_dropdown("parent_id", $arrayParent, set_value('parent_id', $student['parent_id']), "class='form-control' id='parent_id'
										data-plugin-selectTwo ");
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
										echo form_dropdown("route_id", $arrayRoute, set_value('route_id', $student['route_id']), "class='form-control' id='route_id'
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
								</div>
							</div>
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('vehicle_no')?></label>
									<?php
										$arrayVehicle = $this->app_lib->getVehicleByRoute(set_value('route_id', $student['route_id']));
										echo form_dropdown("vehicle_id", $arrayVehicle, set_value('vehicle_id', $student['vehicle_id']), "class='form-control' id='vehicle_id'
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
										echo form_dropdown("hostel_id", $arrayHostel, set_value('hostel_id', $student['hostel_id']), "class='form-control' id='hostel_id'
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
								</div>
							</div>
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('room_name')?></label>
									<?php
										$arrayRoom = $this->app_lib->getRoomByHostel(set_value('hostel_id', $student['hostel_id']));
										echo form_dropdown("room_id", $arrayRoom, set_value('room_id', $student['room_id']), "class='form-control' id='room_id'
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
									<input type="text" class="form-control" name="school_name" value="<?=set_value('school_name', isset($previous_details['school_name']) ? $previous_details['school_name'] : '')?>" />
								</div>
							</div>
							<div class="col-md-6 mb-sm">
								<div class="form-group">
									<label class="control-label"><?=translate('qualification')?></label>
									<input type="text" class="form-control" name="qualification" value="<?=set_value('qualification', isset($previous_details['qualification']) ? $previous_details['qualification'] : '')?>" />
								</div>
							</div>
						</div>
						<div class="row mb-lg">
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label"><?=translate('remarks')?></label>
									<textarea name="previous_remarks" rows="2" class="form-control"><?=set_value('previous_remarks', isset($previous_details['remarks']) ? $previous_details['remarks'] : '')?></textarea>
								</div>
							</div>
						</div>
					</div>
					
					<div class="panel-footer">
						<div class="row">
							<div class="col-md-offset-9 col-md-3">
								<button type="submit" name="update" value="1" class="btn btn-default btn-block"><?=translate('update')?></button>
							</div>
						</div>
					</div>
					</form>
				</div>
			</div>
			
			<!-- student fees report user Interface -->
            <div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#fees">
							<i class="fas fa-money-check"></i> <?=translate('fees')?>
						</a>
					</h4>
				</div>
				<div id="fees" class="accordion-body collapse">
					<div class="panel-body">
						<div class="table-responsive mt-md mb-md">
							<table class="table table-bordered table-condensed table-hover mb-none tbr-top">
								<thead>
									<tr class="text-dark">
										<th>#</th>
										<th><?=translate("fees_type")?></th>
										<th><?=translate("due_date")?></th>
										<th><?=translate("status")?></th>
										<th><?=translate("amount")?></th>
										<th><?=translate("discount")?></th>
										<th><?=translate("fine")?></th>
										<th><?=translate("paid")?></th>
										<th><?=translate("balance")?></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$count = 1;
										$total_fine = 0;
										$total_discount = 0;
										$total_paid = 0;
										$total_balance = 0;
										$total_amount = 0;
										$allocations = $this->fees_model->getInvoiceDetails($student['id']);
										foreach ($allocations as $fee) {
											$deposit = $this->fees_model->getStudentFeeDeposit($fee['allocation_id'], $fee['fee_type_id']);
											$type_discount = $deposit['total_discount'];
											$type_fine = $deposit['total_fine'];
											$type_amount = $deposit['total_amount'];
											$balance = $fee['amount'] - ($type_amount + $type_discount);
											$total_discount += $type_discount;
											$total_fine += $type_fine;
											$total_paid += $type_amount;
											$total_balance += $balance;
											$total_amount += $fee['amount'];
			
										?>
									<tr>
										<td><?php echo $count++;?></td>
										<td><?=$fee['name']?></td>
										<td><?=_d($fee['due_date'])?></td>
										<td><?php 
											$status = 0;
											$labelmode = '';
											if($type_amount == 0) {
												$status = translate('unpaid');
												$labelmode = 'label-danger-custom';
											} elseif($balance == 0) {
												$status = translate('total_paid');
												$labelmode = 'label-success-custom';
											} else {
												$status = translate('partly_paid');
												$labelmode = 'label-info-custom';
											}
											echo "<span class='label ".$labelmode." '>".$status."</span>";
										?></td>
										<td><?php echo $currency_symbol . $fee['amount'];?></td>
										<td><?php echo $currency_symbol . $type_discount;?></td>
										<td><?php echo $currency_symbol . $type_fine;?></td>
										<td><?php echo $currency_symbol . $type_amount;?></td>
										<td><?php echo $currency_symbol . number_format($balance, 2, '.', '');?></td>
									</tr>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr class="text-dark">
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th><?php echo $currency_symbol . number_format($total_amount, 2, '.', ''); ?></th>
										<th><?php echo $currency_symbol . number_format($total_discount, 2, '.', ''); ?></th>
										<th><?php echo $currency_symbol . number_format($total_fine, 2, '.', ''); ?></th>
										<th><?php echo $currency_symbol . number_format($total_paid, 2, '.', ''); ?></th>
										<th><?php echo $currency_symbol . number_format($total_balance, 2, '.', ''); ?></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>

			<!-- student book issued and return report user Interface -->
            <div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#books">
							<i class="fas fa-book-reader"></i> <?=translate('book_issue')?>
						</a>
					</h4>
				</div>
				<div id="books" class="accordion-body collapse">
					<div class="panel-body">
						<div class="table-responsive mt-md mb-md">
							<table class="table table-bordered table-hover table-condensed mb-none">
								<thead>
									<tr>
										<th width="50">#</th>
										<th><?=translate('book_title')?></th>
										<th><?=translate('date_of_issue')?></th>
										<th><?=translate('date_of_expiry')?></th>
										<th><?=translate('fine')?></th>
										<th><?=translate('status')?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$count = 1;
									$builder = $this->db->table('book_issues');
									$builder->orderBy('id', 'desc');
									$builder->where(array('role_id' => 7, 'user_id' => $student['id']));
									$book_result = $builder->get()->getResultArray();
										if (count($book_result)) {
											foreach($book_result as $book):
												?>
										<tr>
											<td><?php echo $count++;?></td>
											<td><?php echo get_type_name_by_id('book', $book['book_id'], 'title');?></td>
											<td><?php echo _d($book['date_of_issue']);?></td>
											<td><?php echo _d($book['date_of_expiry']);?></td>
											<td>
												<?php
												if(empty($book['fine_amount'])){ 
													echo $global_config['currency_symbol'] . "0.00";
												} else {
													echo $global_config['currency_symbol'] . $book['fine_amount'];
												}
												?>
											</td>
											<td>
												<?php
												if($book['status'] == 0)
													echo '<span class="label label-warning-custom">' . translate('pending') . '</span>';
												if ($book['status'] == 1)
													echo '<span class="label label-success-custom">' . translate('issued') . '</span>';
												if($book['status'] == 2)
													echo '<span class="label label-danger-custom">' . translate('rejected') . '</span>';
												if($book['status'] == 3)
													echo '<span class="label label-primary-custom">' . translate('returned') . '</span>';
												?>
											</td>
										</tr>
									<?php
										endforeach;
									}else{
										echo '<tr><td colspan="6"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
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
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#exam_result">
				<i class="fas fa-flask"></i> <?=translate('exam_result')?>
			</a>
		</h4>
	</div>
	<div id="exam_result" class="accordion-body collapse">
		<div class="panel-body">
			<?php 
			$studentID = $student['id'];
			$builder = $this->db->table('timetable_exam');
			$builder->where('class_id', $student['class_id']);
			$builder->where('section_id', $student['section_id']);
			$builder->where('session_id', get_session_id());
			$builder->groupBy('exam_id');
			$variable = $builder->get()->getResultArray();
			foreach ($variable as  $erow) {
				$examID = $erow['exam_id'];
			?>
		        <section class="panel panel-subl-shadow mt-md mb-md">
		            <header class="panel-heading">
		                <h4 class="panel-title"><?=$this->application_model->exam_name_by_id($examID);?></h4>
		            </header>
		            <div class="panel-body">
						<?php
						$result = $this->exam_model->getStudentReportCard($studentID, $examID, get_session_id());
						if (!empty($result['exam'])) {
						$getMarksList = $result['exam'];
						$getExam = $this->db->table('exam')->where(array('id' => $examID))->get()->getRowArray();
						$getSchool = $this->db->table('branch')->where(array('id' => $getExam['branch_id']))->get()->getRowArray();
						$schoolYear = get_type_name_by_id('schoolyear', get_session_id(), 'school_year');
						?>
						<div class="table-responsive">
							<table class="table table-condensed table-bordered mt-sm">
								<thead>
									<tr>
										<th>Subjects</th>
									<?php 
									$markDistribution = json_decode($getExam['mark_distribution'], true);
									foreach ($markDistribution as $id) {
										?>
										<th><?php echo get_type_name_by_id('exam_mark_distribution',$id)  ?></th>
									<?php } ?>
									<?php if ($getExam['type_id'] == 1) { ?>
										<th>Total</th>
									<?php } elseif($getExam['type_id'] == 2) { ?>
										<th>Grade</th>
										<th>Point</th>
									<?php } elseif ($getExam['type_id'] == 3) { ?>
										<th>Total</th>
										<th>Grade</th>
										<th>Point</th>
									<?php } ?>
									</tr>
								</thead>
								<tbody>
								<?php
								$colspan = count($markDistribution) + 1;
								$total_grade_point = 0;
								$grand_obtain_marks = 0;
								$grand_full_marks = 0;
								$result_status = 1;
								foreach ($getMarksList as $row) {
								?>
									<tr>
										<td valign="middle" width="35%"><?=$row['subject_name']?></td>
									<?php 
									$total_obtain_marks = 0;
									$total_full_marks = 0;
									$fullMarkDistribution = json_decode($row['mark_distribution'], true);
									$obtainedMark = json_decode($row['get_mark'], true);
									foreach ($fullMarkDistribution as $i => $val) {
										$obtained_mark = floatval($obtainedMark[$i]);
										$fullMark = floatval($val['full_mark']);
										$passMark = floatval($val['pass_mark']);
										if ($obtained_mark < $passMark) {
											$result_status = 0;
										}

										$total_obtain_marks += $obtained_mark;
										$obtained = $row['get_abs'] == 'on' ? 'Absent' : $obtained_mark;
										$total_full_marks += $fullMark;
										?>
									<?php if ($getExam['type_id'] == 1 || $getExam['type_id'] == 3){ ?>
										<td valign="middle">
											<?php 
												if ($row['get_abs'] == 'on') {
													echo 'Absent';
												} else {
													echo $obtained_mark . '/' . $fullMark;
												}
											?>
										</td>
									<?php } if ($getExam['type_id'] == 2){ ?>
										<td valign="middle">
											<?php 
												if ($row['get_abs'] == 'on') {
													echo 'Absent';
												} else {
													$percentage_grade = ($obtained_mark * 100) / $fullMark;
													$grade = $this->exam_model->get_grade($percentage_grade, $getExam['branch_id']);
													echo $grade['name'];
												}
											?>
										</td>
									<?php } ?>
									<?php
									}
									$grand_obtain_marks += $total_obtain_marks;
									$grand_full_marks += $total_full_marks;
									?>
									<?php if($getExam['type_id'] == 1 || $getExam['type_id'] == 3) { ?>
										<td valign="middle"><?=$total_obtain_marks . "/" . $total_full_marks?></td>
									<?php } if($getExam['type_id'] == 2) { 
										$percentage_grade = ($total_obtain_marks * 100) / $total_full_marks;
										$grade = $this->exam_model->get_grade($percentage_grade, $getExam['branch_id']);
										$total_grade_point += $grade['grade_point'];
										?>
										<td valign="middle"><?=$grade['name']?></td>
										<td valign="middle"><?=number_format($grade['grade_point'], 2, '.', '')?></td>
									<?php } if ($getExam['type_id'] == 3) {
										$colspan += 2;
									$percentage_grade = ($total_obtain_marks * 100) / $total_full_marks;
									$grade = $this->exam_model->get_grade($percentage_grade, $getExam['branch_id']);
									$total_grade_point += isset($grade['grade_point']) ? $grade['grade_point'] : 0;
									?>
									<td valign="middle"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
									<td valign="middle"><?=number_format(isset($grade['grade_point']) ? $grade['grade_point'] : 0, 2, '.', '')?></td>
									<?php } ?>
									</tr>
								<?php } ?>
								<?php if ($getExam['type_id'] == 1 || $getExam['type_id'] == 3) { ?>
									<tr class="text-weight-semibold">
										<td valign="top" >GRAND TOTAL :</td>
										<td valign="top" colspan="<?=$colspan?>"><?=$grand_obtain_marks . '/' . $grand_full_marks; ?>, Average : <?php $percentage = ($grand_obtain_marks * 100) / $grand_full_marks; echo number_format($percentage, 2, '.', '')?>%</td>
									</tr>
									<tr class="text-weight-semibold">
										<td valign="top" >GRAND TOTAL IN WORDS :</td>
										<td valign="top" colspan="<?=$colspan?>">
											<?php
											$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
											echo ucwords($f->format($grand_obtain_marks));
											?>
										</td>
									</tr>
								<?php } if ($getExam['type_id'] == 2) { ?>
									<tr class="text-weight-semibold">
										<td valign="top" >GPA :</td>
										<td valign="top" colspan="<?=$colspan+1?>"><?=number_format(($total_grade_point / count($getMarksList)), 2, '.', '')?></td>
									</tr>
								<?php } if ($getExam['type_id'] == 3) { ?>
									<tr class="text-weight-semibold">
										<td valign="top" >GPA :</td>
										<td valign="top" colspan="<?=$colspan?>"><?=number_format(($total_grade_point / count($getMarksList)), 2, '.', '')?></td>
									</tr>
								<?php } if ($getExam['type_id'] == 1 || $getExam['type_id'] == 3) { ?>
									<tr class="text-weight-semibold">
										<td valign="top" >RESULT :</td>
										<td valign="top" colspan="<?=$colspan?>"><?=$result_status == 0 ? 'Fail' : 'Pass'; ?></td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
				        </div>
				    <?php } else { ?>
						<div class="alert alert-subl mb-none text-center">
							<i class="fas fa-exclamation-triangle"></i> <?=translate('no_information_available')?>
						</div>
				    <?php } ?>
		            </div>
		        </section>
			<?php } ?>
		</div>
	</div>
</div>

            <!-- student parent information user Interface -->
			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#parent">
							<i class="fas fa-users"></i> <?=translate('parent_information')?>
						</a>
					</h4>
				</div>
				<div id="parent" class="accordion-body collapse">
					<div class="panel-body">
						<div class="table-responsive mt-md mb-md">
							<table class="table table-striped table-bordered table-condensed mb-none">
								<tbody>
									<tr>
										<th><?=translate('name')?></th>
										<td><?php echo $getParent[0]['name']?></td>
										<th><?=translate('relation')?></th>
										<td><?php echo $getParent[0]['relation']?></td>
									</tr>
									<tr>
										<th><?=translate('occupation')?></th>
										<td><?php echo $getParent[0]['occupation']?></td>
										<th><?=translate('income')?></th>
										<td><?php echo $global_config['currency_symbol'] . $getParent[0]['income']?></td>
									</tr>
									<tr>
										<th><?=translate('education')?></th>
										<td><?php echo $getParent[0]['education']?></td>
										<th><?=translate('city')?></th>
										<td><?php echo $getParent[0]['city']?></td>
									</tr>
									<tr>
										<th><?=translate('state')?></th>
										<td><?php echo $getParent[0]['state']?></td>
										<th><?=translate('mobile_no')?></th>
										<td><?php echo $getParent[0]['mobileno']?></td>
									</tr>
									<tr>
										<th><?=translate('email')?></th>
										<td colspan="3"><?php echo $getParent[0]['email']?></td>
									</tr>
									<tr class="quick-address">
										<th><?=translate('address')?></th>
										<td colspan="3" height="80px;"><?php echo $getParent[0]['address']?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

            <!-- student parent information user Interface -->
			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#documents">
							<i class="fas fa-folder-open"></i> <?=translate('documents')?>
						</a>
					</h4>
				</div>
				<div id="documents" class="accordion-body collapse">
                    <div class="panel-body">
                        <div class="text-right mb-sm">
                            <a href="javascript:void(0);" onclick="mfp_modal('#addStaffDocuments')" class="btn btn-circle btn-default mb-sm">
                                <i class="fas fa-plus-circle"></i> <?php echo translate('add') . " " . translate('document'); ?>
                            </a>
                        </div>
                        <div class="table-responsive mb-md">
                            <table class="table table-bordered table-hover table-condensed mb-none">
                            <thead>
                                <tr>
                                    <th><?php echo translate('sl'); ?></th>
                                    <th><?php echo translate('title'); ?></th>
                                    <th><?php echo translate('document') . " " . translate('type'); ?></th>
                                    <th><?php echo translate('file'); ?></th>
                                    <th><?php echo translate('remarks'); ?></th>
                                    <th><?php echo translate('created_at'); ?></th>
                                    <th><?php echo translate('actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 1;
                                $builder = $this->db->table('student_documents');
                                $documents = $builder->where('student_id', $student['id'])->get()->getResult();
                                if (count($documents)) {
                                    foreach($documents as $row):
                                    	?>
                                <tr>
                                    <td><?php echo $count++?></td>
                                    <td><?php echo $row->title; ?></td>
                                    <td><?php echo $row->type; ?></td>
                                    <td><?php echo $row->file_name; ?></td>
                                    <td><?php echo $row->remarks; ?></td>
                                    <td><?php echo _d($row->created_at); ?></td>
                                    <td class="min-w-c">
                                        <a href="<?php echo base_url('student/documents_download?file=' . $row->enc_name); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?=translate('download')?>">
                                            <i class="fas fa-cloud-download-alt"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-circle icon btn-default" onclick="editDocument('<?=$row->id?>', 'student')">
                                            <i class="fas fa-pen-nib"></i>
                                        </a>
                                        <?php echo btn_delete('student/document_delete/' . $row->id); ?>
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
        

		</div>
	</div>
</div>

<!-- login authentication and account inactive modal -->
<div id="authentication_modal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title">
				<i class="fas fa-unlock-alt"></i> <?=translate('authentication')?>
			</h4>
		</header>
		<?php echo form_open('student/change_password', array('class' => 'frm-submit')); ?>
        <div class="panel-body">
        	<input type="hidden" name="student_id" value="<?=$student['id']?>">
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

<!-- Documents Details Add Modal -->
<div id="addStaffDocuments" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
    <section class="panel">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-plus-circle"></i> <?php echo translate('add') . " " . translate('document'); ?></h4>
        </div>
        <?php echo form_open_multipart('student/document_create', array('class' => 'form-horizontal frm-submit-data')); ?>
            <div class="panel-body">
                <input type="hidden" name="patient_id" value="<?php echo $student['id']; ?>">
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?php echo translate('title'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="document_title" id="adocument_title" value="" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo translate('document') . " " . translate('type'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="document_category" id="adocument_category" value="" />
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
        <?php echo form_open_multipart('student/document_update', array('class' => 'form-horizontal frm-submit-data')); ?>
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
                    <label class="col-md-3 control-label"><?php echo translate('document') . " " . translate('type'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="document_category" id="edocument_category" value="" />
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
                            <i class="fas fa-plus-circle"></i> <?php echo translate('update'); ?>
                        </button>
                        <button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
                    </div>
                </div>
            </footer>
        <?php echo form_close(); ?>
    </section>
</div>

<script type="text/javascript">
	var authenStatus = "<?=$student['active']?>";
</script>