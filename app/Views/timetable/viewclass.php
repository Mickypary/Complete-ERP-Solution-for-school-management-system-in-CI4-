<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();

?>





<?php $widget = (is_superadmin_loggedin() ? 4 : 6); ?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><?=translate('select_ground')?></h4>
	<?php if(get_permission('class_timetable', 'is_add')) { ?>
		<div class="panel-btn">
			<a href="<?=base_url('timetable/set_classwise')?>" class="btn btn-default btn-circle">
				<i class="fas fa-plus-circle"></i> <?=translate('add') . " " . translate('schedule')?>
			</a>
		</div>
	<?php } ?>
	</header>
	<?php echo form_open(uri_string(), array('class' => 'validate')); ?>
	<div class="panel-body">
		<div class="row mb-sm">
			<?php if (is_superadmin_loggedin()): ?>
			<div class="col-md-4 mb-sm">
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

<?php if(isset($timetables)): ?>
	<section class="panel appear-animation mt-sm" data-appear-animation="<?php echo $global_config['animations'];?>" data-appear-animation-delay="100">
		<header class="panel-heading">
			<div class="panel-btn">
			<?php if (get_permission('class_timetable', 'is_edit')): ?>
				<a class="edit_modal btn btn-default btn-circle icon" href="javascript:void(0);">
					<i class="fas fa-pen-nib"></i>
				</a>
			<?php endif; ?>
				<button onclick="fn_printElem('printResult')" class="btn btn-default btn-circle icon"><i class="fas fa-print"></i></button>
			</div>
			<h4 class="panel-title"><i class="fas fa-user-clock"></i> <?=translate('schedule') . " " . translate('list')?></h4>
		</header>
		<div class="panel-body">
			<?php if(count($timetables) > 0){ ?>
			<div class="table-responsive">
				<div id="printResult">
					<!-- hidden school information prints -->
					<div class="visible-print">
						<center>
							<h4 class="text-dark text-weight-bold"><?=$global_config['institute_name']?></h4>
							<h5 class="text-dark"><?=$global_config['address']?></h5>
							<h5 class="text-dark text-weight-bold">Class Timetable</h5>
							<h5 class="text-dark">
								<?php 
								echo translate('class') . ' : ' . get_type_name_by_id('class', $class_id);
								echo ' ( ' . translate('section') . ' : ' .  get_type_name_by_id('section', $section_id) .  ' )';
								?>
							</h5>
							<hr>
						</center>
					</div>
					<table class="table table-bordered table-hover table-condensed text-dark">
						<tbody>
						<?php
						$days = array(
							'sunday',
							'monday',
							'tuesday',
							'wednesday',
							'thursday',
							'friday',
							'saturday'
						);
						$mapfunction = function($s) {return $s->day;};
						$count = array_count_values(array_map($mapfunction, $timetables));
						$max = max($count);
						foreach ($days as $key => $day):
							echo '<tr>';
								echo '<td class="timetable">' . strtoupper($day) . '</td>';
								$row_count = 0;
								foreach ($timetables as $timetable){
									if($timetable->day == $day) {
										$row_count ++;
										echo '<td class="center">';
										if($timetable->break == 0){
											echo '<strong>' . get_type_name_by_id('subject', $timetable->subject_id) . '</strong><br>';
										} else{
											echo '<strong>BREAK</strong><br>';
										}
										echo '<small> (' . date("g:i A", strtotime($timetable->time_start)) . ' - ' . date("g:i A", strtotime($timetable->time_end)) . ')</small><br>';
										if($timetable->break == 0)
											echo '<small>' . translate('teacher') . ' : ' . get_type_name_by_id('staff', $timetable->teacher_id) . '</small>';
										echo ($timetable->class_room != '' ? '<br>' . translate('class_room') . ' : ' . $timetable->class_room : '');
										
										echo '</td>';
									}
								}
								while($row_count<$max) {
									echo '<td class="center">N/A</td>';
									$row_count++;
								}
							echo '</tr>';
						endforeach;
						?>
						</tbody>
					</table>
				</div>
			</div>
			<?php
				}else{
					echo '<div class="alert alert-subl mt-md text-center"><strong>Oops!</strong> No Schedule Was Made !</div>';
				}
			?>
		</div>
	</section>
	
	<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
		<section class="panel">
			<div class="panel-heading">
				<h4 class="panel-title">
					<i class="fas fa-pen-nib"></i> <?=translate('schedule') . ' ' . translate('edit')?>
				</h4>
			</div>
			<?php echo form_open('timetable/update_classwise', array('target' => '_blank', 'class' => ' validate')); ?>
			<div class="panel-body">
				<div class="form-group mt-sm mb-lg">
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
				<?php 
					$data = array(
						'branch_id' => $branch_id,
						'class_id' => $class_id,
						'section_id' => $section_id
					);
					echo form_hidden($data);
				?>
			</div>
			<footer class="panel-footer">
				<div class="text-right">
					<button type="submit" id="submit" class="btn btn-default"><?=translate('done')?></button>
					<button class="btn btn-default modal-dismiss"><?=translate('cancel')?></button>
				</div>
			</footer>
			<?php echo form_close();?>
		</section>
	</div>
<?php endif;?>

<script type="text/javascript">
	$(document).ready(function () {
		"use strict";
		
		$(document).on('click', '#submit', function () {
			$.magnificPopup.close();
		});
		
		$(document).on('click', '.edit_modal', function () {
			mfp_modal('#modal');
		});
	});
</script>