<?php
use App\Libraries\App_lib;
use App\Models\ApplicationModel;
use App\Models\ExamModel;

$this->app_lib = new App_lib();
$this->application_model = new ApplicationModel();
$this->db = \Config\Database::connect();
$this->exam_model = new ExamModel();

// $getExam = $this->db->table('exam')->where(array('id' => $examID))->get()->getRowArray();

?>




<?php $widget = (is_superadmin_loggedin() ? 2 : 3); ?>
<div class="row">
	<div class="col-md-12">
		<section class="panel">
		<?php echo form_open(uri_string(), array('class' => 'validate'));?>
			<header class="panel-heading">
				<h4 class="panel-title"><?=translate('select_ground')?></h4>
			</header>
			<div class="panel-body">
				<div class="row mb-sm">
					<?php if (is_superadmin_loggedin()): ?>
					<div class="col-md-2 mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
						</div>
					</div>
					<?php endif; ?>
					<div class="col-md-<?php echo $widget; ?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('exam')?> <span class="required">*</span></label>
							<?php
								if(isset($branch_id)){
									$arrayExam = array("" => translate('select'));
									$exams = $this->db->table('exam')->getWhere(array('branch_id' => $branch_id,'session_id' => get_session_id()))->getResult();
									foreach ($exams as $row){
										$arrayExam[$row->id] = $this->application_model->exam_name_by_id($row->id);
									}
								} else {
									$arrayExam = array("" => translate('select_branch_first'));
								}
								echo form_dropdown("exam_id", $arrayExam, set_value('exam_id'), "class='form-control' id='exam_id' required data-plugin-selectTwo
								data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="col-md-2 mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('exam_type')?> <span class="required">*</span></label>
							<?php
								// $arrayClass = $this->app_lib->getExamType($branch_id);
							$arrayType = array("" => translate('select'));
								$builder = $this->db->table('exam');
                				$result = $builder->get()->getResult();
                				// print_r($result);
                				foreach ($result as $row){
										// $arrayType[$row->id] = $row->type_id;
										if ($row->type_id == 4) {
											$arrayType[$row->type_id] = 'End Of Xmas Term';

										}elseif($row->type_id == 3) {
											// $arrayType[$row->type_id] = 'Mark and GPA';
											$arrayType[$row->type_id] = 'Mid';
										}elseif($row->type_id == 5) {
											$arrayType[$row->type_id] = 'End Of Lent Term';
										}elseif ($row->type_id == 6) {
											$arrayType[$row->type_id] = 'End Of 3rd Term';
										}
									}
								echo form_dropdown("type_id", $arrayType, set_value('type_id'), "class='form-control' 
								required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="col-md-2 mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('exam_type_rel')?> <span class="required">*</span></label>
							<?php
								// $arrayClass = $this->app_lib->getExamType($branch_id);
							$arrayTypes = array("" => translate('select'));
								$builder = $this->db->table('exam');
                				$result = $builder->get()->getResult();
                				// print_r($result);
                				foreach ($result as $row){
										// $arrayType[$row->id] = $row->type_id;
										if ($row->type_id == 4) {
											$arrayTypes[$row->type_id] = 'End Of Xmas Term';

										}elseif($row->type_id == 5) {
											$arrayTypes[$row->type_id] = 'End Of Lent Term';
										}elseif ($row->type_id == 6) {
											$arrayTypes[$row->type_id] = 'End Of 3rd Term';
										}
									}
								echo form_dropdown("type_rel_id", $arrayType, set_value('type_rel_id'), "class='form-control' 
								required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="col-md-2 mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
							<?php
								$arrayClass = $this->app_lib->getClass($branch_id);
								echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)'
								required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="col-md-<?php echo $widget; ?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
							<?php
								$arraySection = $this->app_lib->getSections(set_value('class_id'), false);
								echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id' required
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label class="control-label"><?=translate('subject')?> <span class="required">*</span></label>
							<?php
								if(!empty(set_value('class_id'))) {
									$arraySubject = array("" => translate('select'));
									$assigns = $this->db->table('subject_assign')->getWhere(array('class_id' => set_value('class_id'), 'section_id' => set_value('section_id')))->getResult();
									foreach ($assigns as $row){
										$arraySubject[$row->subject_id] = get_type_name_by_id('subject', $row->subject_id);
									}
								} else {
									$arraySubject = array("" => translate('select_class_first'));
								}
								echo form_dropdown("subject_id", $arraySubject, set_value('subject_id'), "class='form-control' id='subject_id' required
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-offset-10 col-md-2">
						<button type="submit" name="search" value="1" class="btn btn btn-default btn-block"> <i class="fas fa-filter"></i> <?=translate('filter')?></button>
					</div>
				</div>
			</footer>
			<?php echo form_close();?>
		</section>
		
		<?php if (isset($student)): ?>

		<section class="panel appear-animation" data-appear-animation="<?php echo $global_config['animations'];?>" data-appear-animation-delay="100">
			<?php echo form_open('exam/mark_save', array('class' => 'frm-submit-msg'));
				$data = array(
					'class_id' => $class_id,
					'section_id' => $section_id,
					'exam_id' => $exam_id,
					'subject_id' => $subject_id,
					'session_id' => get_session_id(),
					'branch_id' => $branch_id,
					'type_id' => $type_id,
					'type_rel_id' => $type_rel_id,
				);
				echo form_hidden($data);
			?>
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-users"></i> <?=translate('mark_entries')?></h4>
			</header>
			<div class="panel-body">
				<?php if (count($student) && count($timetable_detail)){ ?>

				<div class="table-responsive mt-md mb-lg">
					<table class="table table-bordered table-condensed mb-none">
						<thead>
							<tr>
								<th><?=translate('sl')?></th>
								<th><?=translate('student_name')?></th>
								<th><?=translate('category')?></th>
								<th><?=translate('register_no')?></th>
								<th><?=translate('roll')?></th>
								<th>IsAbsent</th>
							<?php
							$distributions = json_decode($timetable_detail['mark_distribution'], true);
							// print_r($distributions);
							// die();
							foreach ($distributions as $i => $value) {
								?>
								<th><?php echo strtoupper(get_type_name_by_id('exam_mark_distribution', $i)) . " (" . $value['full_mark'] . ")" ?></th>
							<?php } ?>
							<th><?= strtoupper(translate('total')); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php $count = 1; foreach ($student as $key => $row): ?>

							<?php
							$studentID = $row['student_id'];
							$sessionID = get_session_id();

							$result = $this->exam_model->getStudentReportCard($studentID, $exam_id, $sessionID);
							$student = $result['student'];
							$getMarksList = $result['exam'];
							// print_r($getMarksList);
							$obtainedMark = json_decode($row['get_mark'], true);
							// print_r($obtainedMark);

							?>





							<tr>
								<input type="hidden" name="mark[<?=$key?>][student_id]" value="<?=$row['student_id']?>">
								<td><?php echo $count++; ?></td>
								<td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
								<td><?php echo get_type_name_by_id('student_category', $row['category_id']); ?></td>
								<td><?php echo $row['register_no']; ?></td>
								<td><?php echo $row['roll']; ?></td>
								<td>
									<div class="checkbox-replace"> 
										<label class="i-checks"><input type="checkbox" name="mark[<?=$key?>][absent]" <?=($row['get_abs'] == 'on' ? 'checked' : ''); ?>><i></i></label>
									</div>
								</td>
								<?php
								$getDetails = json_decode($row['get_mark'], true);
								// $getTotal = json_decode($row['get_total'], true);

								$total_obtain_marks = 0;
								$total_full_marks = 0;
								$grand_obtain_marks = 0;

								$total_grade_point = 0;
								$grand_full_marks = 0;

								$sum = 0;
								foreach ($distributions as $id => $ass) {
									// print_r($getDetails[$id]);
								// die();

									$fullMark = floatval($ass['full_mark']);
									$passMark = floatval($ass['pass_mark']);




									$existMark = isset($getDetails[$id]) ? floatval($getDetails[$id])  : '';

									$total_obtain_marks += intval($existMark);
									$total_full_marks += $fullMark;
									// print_r($existMark);

									?>
								<td class="min-w-sm">
									<div class="form-group">
										<input type="text" class="form-control" autocomplete="off" name="mark[<?=$key?>][assessment][<?=$id?>]" value="<?=$existMark?>">
										<span class="error"></span>
									</div>
								</td>
								<?php } ?>

								<?php
									$grand_obtain_marks += $total_obtain_marks;
									$grand_full_marks += $total_full_marks;
								?>

								<td>
									<div class="form-group">
										<input type="hidden" class="form-control" autocomplete="off" name="mark[<?=$key?>][sum][4]" id="mark[]"  value="<?= $total_obtain_marks ?>"><?= $total_obtain_marks ?>
										<span class="error"></span>
									</div>
								</td>

							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<?php } else { echo '<div class="alert alert-subl mt-md text-center">' . translate('no_information_available') . '</div>'; } ?>
			</div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-offset-10 col-md-2">
						<button type="submit" name="save" value="1" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
							<i class="fas fa-plus-circle"></i> <?=translate('save')?>
						</button>
					</div>
				</div>
			</div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-offset-10 col-md-2">
						<button type="submit" name="mid" value="1" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
							<i class="fas fa-plus-circle"></i> <?=translate('update_mid')?>
						</button>
					</div>
				</div>
			</div>
			<?php echo form_close(); ?>
		</section>
		<?php endif; ?>
	</div>
</div>
	
<script type="text/javascript">
	$(document).ready(function () {
		$('#branch_id').on('change', function() {
			var branchID = $(this).val();
			getClassByBranch(branchID);
			getExamByBranch(branchID);
			$('#subject_id').html('').append('<option value=""><?=translate("select")?></option>');
		});

		$('#section_id').on('change', function() {
			var classID = $('#class_id').val();
			var sectionID =$(this).val();
			$.ajax({
				url: base_url + 'subject/getByClassSection',
				type: 'POST',
				data: {
					classID: classID,
					sectionID: sectionID
				},
				success: function (data) {
					$('#subject_id').html(data);
				}
			});
		});

	});
</script>
