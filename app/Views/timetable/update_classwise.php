<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();
$this->db = \Config\Database::connect();


?>




<section class="panel appear-animation" data-appear-animation="<?=$global_config['animations'] ?>">
	<?php echo form_open('timetable/class_save/update', array('class' => 'frm-submit')); ?>
	<header class="panel-heading">
		<h4 class="panel-title"><i class="far fa-clock"></i>
			<?php
			if(count($timetables) > 0){
				echo translate('class') . " " . get_type_name_by_id('class', $class_id);
				echo ' (' . translate('section') . ': ' .  get_type_name_by_id('section', $section_id) .  ') - ' . ucfirst($day) .  ' - ';
			}
			echo translate('schedule') . ' ' . translate('edit');
			?>
		</h4>
	</header>
	<div class="panel-body" >
		<?php if(count($timetables) > 0){ ?>
		<div class="table-responsive mb-md">
			<table class="table table-condensed table-bordered mt-md">
				<thead>
					<th> - BREAK</th>
					<th><?=translate('subject')?> <span class="required">*</span></th>
					<th><?=translate('teacher')?> <span class="required">*</span></th>
					<th><?=translate('starting_time')?> <span class="required">*</span></th>
					<th><?=translate('ending_time')?> <span class="required">*</span></th>
					<th><?=translate('class_room')?></th>
				</thead>
				<tbody id="timetable_entry">
						<?php
						foreach ($timetables as $key => $timetable){ 
						$id = $timetable->id;
						$break = ($timetable->break == 1 ? 'disabled' : '');
						?>
						<tr>
							<?php echo form_hidden(array('i[]' => $timetable->id)); ?>
							<td class="center" width="90">
								<div class="checkbox-replace"> 
									<label class="i-checks">
										<input type="checkbox" name="timetable[<?=$key?>][break]" <?=($timetable->break == 1 ? 'checked' : ''); ?>>
										<i></i>
									</label>
								</div>
							</td>
							<td width="20%">
								<div class="form-group">
									<?php
										$array = array("" => translate('select'));
										$subjects = $this->db->table('subject')->getWhere(array('branch_id' => $branch_id))->getResult();
										foreach ($subjects as $subject){
											$array[$subject->id] = $subject->name;
										}
										echo form_dropdown("timetable[$key][subject]", $array, $timetable->subject_id, "class='form-control subject' data-plugin-selectTwo $break
										data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
									<span class="error"></span>
								</div>
							</td>
							<td width="20%">
								<div class="form-group">
									<?php
										$arrayTeacher = $this->app_lib->getStaffList($branch_id, 3);
										echo form_dropdown("timetable[$key][teacher]", $arrayTeacher, $timetable->teacher_id, "class='form-control'
										data-plugin-selectTwo data-width='100%' ");
									?>
									<span class="error"></span>
								</div>
							</td>
							<td>
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><i class="far fa-clock"></i></span>
										<input type="text" name="timetable[<?=$key?>][time_start]" data-plugin-timepicker class="form-control" value="<?=$timetable->time_start?>"
										data-plugin-options='{ "minuteStep": 5 }'>
									</div>
									<span class="error"></span>
								</div>
							</td>
							<td>
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon">
										<i class="far fa-clock"></i>
										</span>
										<input type="text" name="timetable[<?=$key?>][time_end]" data-plugin-timepicker class="form-control" value="<?=$timetable->time_end?>"
										data-plugin-options='{ "minuteStep": 5 }'>
									</div>
									<span class="error"></span>
								</div>
							</td>
							<td class="timet-td">
								<input type="text" class="form-control" name="timetable[<?=$key?>][class_room]" value="<?=$timetable->class_room?>">
								<button type="button" class="btn btn-danger removeTR"><i class="fas fa-times"></i> </button>
							</td>
						</tr>				
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?php
			}else{
				echo '<div class="alert alert-subl mt-md text-center"><strong>Oops!</strong> No Schedule Was Made !</div>';
			}
		?>
	</div>
	<footer class="panel-footer">
		<div class="row">
			<div class="col-md-offset-10 col-md-2">
				<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing"><i class="fas fa-pencil-square-o"></i> <?=translate('update')?></button>
			</div>
		</div>
	</footer>
	<?php 
		$data = array(
			'class_id' 		=> $class_id,
			'section_id' 	=> $section_id,
			'day' 			=> $day,
			'branch_id' 	=> $branch_id
		);
		echo form_hidden($data);
		echo form_close();
	?>
</section>

<script type="text/javascript">
	$(document).ready(function () {
		$(document).on('change', "#timetable_entry input[type='checkbox']", function() {
			$(this).closest('tr').find('select').prop('disabled', this.checked);
		});
		
		$("#timetable_entry").on('click', '.removeTR', function () {
			$(this).parent().parent().remove();
		});
	});
</script>