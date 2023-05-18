<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();
$this->db = \Config\Database::connect();

?>



<?php $widget = (is_superadmin_loggedin() ? 3 : 4); ?>
<div class="row">
	<div class="col-md-12">
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
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' required onchange='getClassByBranch(this.value)'
								data-width='100%' data-plugin-selectTwo data-minimum-results-for-search='Infinity'");
							?>
						</div>
					</div>
					<?php endif; ?>
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
					<div class="col-md-<?php echo $widget; ?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('day')?> <span class="required">*</span></label>
							<?php
								$arrayDay = array(
									"sunday" => "Sunday",
									"monday" => "Monday",
									"tuesday" => "Tuesday",
									"wednesday" => "Wednesday",
									"thursday" => "Thursday",
									"friday" => "Friday",
									"saturday" => "Saturday"
								);
								echo form_dropdown("day", $arrayDay, set_value('day'), "class='form-control' required
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
		
		<?php if(!empty($branch_id) && !empty($class_id) && !empty($day)):?>
		<section class="panel appear-animation" data-appear-animation="<?php echo $global_config['animations']?>" data-appear-animation-delay="100">
			<?php echo form_open('timetable/class_save/new', array('class' => 'frm-submit')); ?>
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-clock"></i> <?=translate('add') . " " . translate('schedule')?></h4>
			</header>
			<div class="panel-body" >
				<div class="table-responsive">
					<table class="table table-bordered table-condensed mt-md">
						<thead>
							<th> - BREAK</th>
							<th><?=translate('subject')?> <span class="required">*</span></th>
							<th><?=translate('teacher')?> <span class="required">*</span></th>
							<th><?=translate('starting_time')?> <span class="required">*</span></th>
							<th><?=translate('ending_time')?> <span class="required">*</span></th>
							<th><?=translate('class_room')?></th>
						</thead>
						<tbody id="timetable_entry_append">
							<tr class="iadd">
								<td class="center" width="90">
									<div class="checkbox-replace"> 
										<label class="i-checks">
											<input type="checkbox" name="timetable[0][break]"><i></i>
										</label>
									</div>
								</td>
								<td width="20%">
									<div class="form-group">
										<?php
											$arraySubject = array("" => translate('select'));
											$subjectAssign = $this->db->table('subject_assign')->getWhere(array(
												'class_id' => $class_id,
												'section_id' => $section_id,
												'session_id' => get_session_id(),
												'branch_id' => $branch_id
											))->getResult();
											foreach ($subjectAssign as $assign){
												$arraySubject[$assign->subject_id] = get_type_name_by_id('subject', $assign->subject_id);
											}
											echo form_dropdown("timetable[0][subject]", $arraySubject, "", "class='form-control' data-plugin-selectTwo
											data-width='100%' data-minimum-results-for-search='Infinity' ");
										?>
										<span class="error"></span>
									</div>
								</td>
								<td width="20%">
									<div class="form-group">
										<?php
											$arrayTeacher = $this->app_lib->getStaffList($branch_id, 3);
											echo form_dropdown("timetable[0][teacher]", $arrayTeacher, "", "class='form-control'
											data-plugin-selectTwo data-width='100%' ");
										?>
										<span class="error"></span>
									</div>
								</td>
								<td>
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i class="far fa-clock"></i></span>
											<input type="text" name="timetable[0][time_start]" data-plugin-timepicker data-plugin-options ="{'timeFormat': 'HH:mm:ss'}" class="form-control" />
										</div>
										<span class="error"></span>
									</div>
								</td>
								<td>
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i class="far fa-clock"></i></span>
											<input type="text" name="timetable[0][time_end]" data-plugin-timepicker class="form-control" />
										</div>
										<span class="error"></span>
									</div>
								</td>
								<td>
									<input type="text" class="form-control" name="timetable[0][class_room]" value="">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<input type="hidden" name="class_id" id="classID" value="<?=$class_id?>" />
				<input type="hidden" name="section_id" id="sectionID" value="<?=$section_id?>" />
				<input type="hidden" name="day" id="day" value="<?=$day?>" />
				<input type="hidden" name="branch_id" id="branchID" value="<?=$branch_id?>" />
				<button type="button" class="btn btn-default mt-xs mb-md" onclick="append_timetable_entry()">
					<i class="fas fa-plus-circle"></i> <?=translate('add_more')?>
				</button>
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
		
		<script type="text/javascript">
			$(document).on('change', "#timetable_entry_append input[type='checkbox']", function() {
				$(this).closest('tr').find('select').prop('disabled', this.checked);
			})
			
			function append_timetable_entry(){
           		var lenght_div = $('#timetable_entry_append .iadd').length;
           		// const div = document.getElementById("timetable_entry_append");
           		// const list = div.querySelectorAll(".iadd").length;
				$("#timetable_entry_append").append(getDynamicInput(lenght_div));
				
				$(".selectTwo").each(function() {
					var $this = $(this);
					$this.themePluginSelect2({});
				});
				$(".timepicker").each(function() {
					var $this = $(this);
					$this.themePluginTimePicker({});
				});
			}
			
			function getDynamicInput(value) {
				var row = "";
				row += '<tr class="iadd">';
				row += '<td class="center" width="90"><div class="checkbox-replace">';
				row += '<label class="i-checks"><input type="checkbox" name="timetable[' + value + '][break]" id="' + value + '"><i></i>';
				row += '</label></div></td>';
				row += '<td width="20%"><div class="form-group">';
				row += '<select id="subject_id_' + value + '" name="timetable[' + value + '][subject]" class="form-control selectTwo" data-width="100%">';
				row += '<option value=""><?php echo translate('select'); ?></option>';
<?php foreach ($subjectAssign as $assign): ?>
				row += '<option value="<?php echo $assign->subject_id ?>"><?php echo get_type_name_by_id('subject', $assign->subject_id) ?></option>';
<?php endforeach; ?>
				row += '</select>';
				row += '<span class="error"></span></div></td>';
				row += '<td width="20%"><div class="form-group">';
				row += '<select  id="teacher_id_' + value + '" name="timetable[' + value + '][teacher]" class="form-control selectTwo" data-width="100%">';
<?php foreach ($arrayTeacher as $key => $value): ?>
				row += '<option value="<?php echo $key ?>"><?php echo $value ?></option>';
<?php endforeach; ?>
				row += '</select>';
				row += '<span class="error"></span></div></td>';
				row += '<td><div class="form-group">';
				row += '<div class="input-group">';
				row += '<span class="input-group-addon"><i class="far fa-clock"></i></span>';
				row += '<input type="text" name="timetable[' + value + '][time_start]" class="form-control timepicker" >';
				row += '</div><span class="error"></span></div></td>';
				row += '<td><div class="form-group">';
				row += '<div class="input-group">';
				row += '<span class="input-group-addon"><i class="far fa-clock"></i></span>';
				row += '<input type="text" name="timetable[' + value + '][time_end]" class="form-control timepicker" >';
				row += '</div><span class="error"></span></div></td>';
				row += '<td class="timet-td">';
				row += '<input type="text" class="form-control" name="timetable[' + value + '][class_room]" value="">';
				row += '<button type="button" class="btn btn-danger removeTR"><i class="fas fa-times"></i> </button>';
				row += '</td>';
				row += '</tr>';
				return row;
			}
		</script>
		<?php endif;?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$("#timetable_entry_append").on('click', '.removeTR', function () {
			$(this).parent().parent().remove();
		});
	});
</script>