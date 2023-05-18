<?php
use App\Libraries\App_lib;
use App\Models\ApplicationModel;

$this->app_lib = new App_lib();
$this->db = \Config\Database::connect();
$this->application_model = new ApplicationModel();

?>





<?php $widget = (is_superadmin_loggedin() ? 3 : 4); ?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><?=translate('select_ground')?></h4>
	</header>
	<?php echo form_open(uri_string(), array('class' => 'validate')); ?>
	<div class="panel-body">
		<div class="row mb-sm">
			<?php if (is_superadmin_loggedin()): ?>
			<div class="col-md-3 mb-sm">
				<div class="form-group">
					<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
					<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' required id='branch_id'
						data-width='100%' data-plugin-selectTwo data-minimum-results-for-search='Infinity'");
					?>
				</div>
			</div>
			<?php endif; ?>
			<div class="col-md-<?php echo $widget; ?> mb-sm">
				<div class="form-group">
					<label class="control-label"><?=translate('exam_name')?> <span class="required">*</span></label>
					<?php
						$arrayExam = array("" => translate('select_branch_first'));
						if(!empty($branch_id)){
							$exams = $this->db->table('exam')->getWhere(array('branch_id' => $branch_id,'session_id' => get_session_id()))->getResult();
							foreach ($exams as $exam){
								$arrayExam[$exam->id] = $this->application_model->exam_name_by_id($exam->id);
							}
						}
						echo form_dropdown("exam_id", $arrayExam, set_value('exam_id'), "class='form-control' id='exam_id' required
						data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
					?>
				</div>
			</div>
			<div class="col-md-<?php echo $widget; ?> mb-sm">
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
						$arraySection = $this->app_lib->getSections(set_value('class_id'));
						echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id' required 
						data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
					?>
				</div>
			</div>
		</div>
	</div>
	<footer class="panel-footer">
		<div class="row">
			<div class="col-md-offset-10 col-md-2">
				<button type="submit" class="btn btn btn-default btn-block"> <i class="fas fa-filter"></i> <?=translate('filter')?></button>
			</div>
		</div>
	</footer>
	<?php echo form_close();?>
</section>

<?php if(isset($subjectassign)):?>
	<section class="panel appear-animation" data-appear-animation="<?php echo $global_config['animations'];?>" data-appear-animation-delay="100">
		<?php
		echo form_open('timetable/exam_create', array('class' => 'frm-submit-msg'));
		$data = array(
			'exam_id' => $exam_id,
			'class_id' => $class_id,
			'section_id' => $section_id,
			'branch_id' => $branch_id
		);
		echo form_hidden($data);
		?>
		<header class="panel-heading">
			<h4 class="panel-title"><i class="far fa-clock"></i> <?=translate('add') . " " . translate('schedule')?></h4>
		</header>
		<div class="panel-body" >
			<div class="table-responsive mb-md">
				<table class="table table-bordered mt-md">
					<thead>
						<th><?=translate('subject')?> <span class="required">*</span></th>
						<th><?=translate('date')?> <span class="required">*</span></th>
						<th><?=translate('starting_time')?> <span class="required">*</span></th>
						<th><?=translate('ending_time')?> <span class="required">*</span></th>
						<th><?=translate('hall_room')?> <span class="required">*</span></th>
<?php
// getting exist exam distribution
$examDistribution = $this->db->table('exam')->where('id', $exam_id)->get()->getRow()->mark_distribution;
$distribution = json_decode($examDistribution, true);
// print_r($subjectassign[0]);
// die();
foreach ($distribution as $id) {
?>
						<th><?=get_type_name_by_id('exam_mark_distribution', $id)?> <span class="required">*</span></th>
<?php } ?>	
					</thead>
					<tbody>
						<?php
						if (count($subjectassign)){
							foreach ($subjectassign as $key => $row):
								$subjectID = $row['subject_id'];
						?>
						<tr>
							<td class="min-w-sm">
								<input type="hidden" name="timetable[<?=$key?>][subject_id]" value="<?=$subjectID?>"><?=get_type_name_by_id('subject', $subjectID)?>
							</td>
							<td class="min-w-sm">
								<div class="form-group mb-none">
									<input type="text" class="form-control" data-plugin-datepicker data-plugin-options='{ "todayHighlight" : true }' autocomplete="off"
									name="timetable[<?=$key?>][date]" value="<?=$row['exam_date']?>" />
									<span class="error"></span>
								</div>
							</td>
							<td class="min-w-sm">
								<div class="form-group mb-none">
									<div class="input-group">
										<span class="input-group-addon"><i class="far fa-clock"></i></span>
										<input type="text" name="timetable[<?=$key?>][time_start]" data-plugin-timepicker class="form-control" autocomplete="off"
										data-plugin-options='{ "minuteStep": 5 }' value="<?=$row['time_start']?>">
										<span class="error"></span>
									</div>
									<span class="error"></span>
								</div>
							</td>
							<td class="min-w-sm">
								<div class="form-group mb-none">
									<div class="input-group">
										<span class="input-group-addon"><i class="far fa-clock"></i></span>
										<input type="text" name="timetable[<?=$key?>][time_end]" data-plugin-timepicker class="form-control" autocomplete="off"
										data-plugin-options='{ "minuteStep": 5 }' value="<?=$row['time_end']?>">
									</div>
									<span class="error"></span>
								</div>
							</td>
							<td class="min-w-sm">
								<div class="form-group mb-none">
									<?php
										if(!empty($branch_id)){
											$hall_array = array("" => translate('not_selected'));
											$halls = $this->db->table('exam_hall')->getWhere(array('branch_id' => $branch_id))->getResult();
											foreach ($halls as $hall){
												$hall_array[$hall->id] = $hall->hall_no;
											}
										}else{
											$hall_array = array("" => translate('select_branch_first'));
										}
										echo form_dropdown("timetable[$key][hall_id]", $hall_array, $row['hall_id'], "class='form-control' data-plugin-selectTwo
										data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
									<span class="error"></span>
								</div>
							</td>

<?php
// getting exist mark
$getMark = json_decode($row['mark_distribution'], true);
// print_r($getMark);
// die();
foreach ($distribution as $id) {
	$full_mark = isset($getMark[$id]['full_mark']) ? $getMark[$id]['full_mark'] : "";
	$pass_mark = isset($getMark[$id]['pass_mark']) ? $getMark[$id]['pass_mark'] : "";
	?>

							<td>
								<div class="mark-inline">
								<div class="form-group mb-none mr-xs">
									<input type="text" class="form-control" style="width: 86px" autocomplete="off" placeholder="Full Mark" name="timetable[<?=$key?>][full_mark][<?=$id?>]" value="<?=$full_mark?>" />
									<span class="error"></span>
								</div>
								<div class="form-group mb-none">
									<input type="text" class="form-control" style="width: 86px" autocomplete="off" placeholder="Pass Mark" name="timetable[<?=$key?>][pass_mark][<?=$id?>]" value="<?=$pass_mark?>" />
									<span class="error"></span>
								</div>
								</div>
							</td>
<?php } ?>
						</tr>
						<?php  
							endforeach; 
						} else {
							echo '<tr><td colspan="7"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-offset-10 col-md-2">
					<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<i class="fas fa-plus-circle"></i> <?=translate('save')?>
					</button>
				</div>
			</div>
		</footer>
		<?php echo form_close(); ?>
	</section>
<?php endif;?>

<script type="text/javascript">
	$(document).ready(function () {
		$('#branch_id').on("change", function() {
			var branchID = $(this).val();
			getClassByBranch(branchID);
			getExamByBranch(branchID);
		});
	});
</script>