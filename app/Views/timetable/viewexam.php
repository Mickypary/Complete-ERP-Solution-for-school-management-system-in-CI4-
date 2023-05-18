<?php
use App\Libraries\App_lib;
use App\Models\ApplicationModel;

$this->app_lib = new App_lib();
$this->application_model = new ApplicationModel();

?>




<?php $widget = (is_superadmin_loggedin() ? 4 : 6); ?>
<div class="row">
	<div class="col-md-12">

		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><?=translate('select_ground')?></h4>
			</header>
			<?php echo form_open(uri_string(), array('class' => 'validate'));?>
			<div class="panel-body">
				<div class="row mb-sm">
				<?php if (is_superadmin_loggedin() ): ?>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' onchange='getClassByBranch(this.value)'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
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
						<button type="submit" name="search" value="1" class="btn btn-default btn-block"> <i class="fas fa-filter"></i> <?=translate('filter')?></button>
					</div>
				</div>
			</footer>
			<?php echo form_close();?>
		</section>

	<?php if (isset($examlist)): ?>
		<section class="panel appear-animation mt-sm" data-appear-animation="<?=$global_config['animations'] ?>" data-appear-animation-delay="100">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-clock"></i> <?=translate('schedule') . " " . translate('list')?></h4>
			</header>
			<div class="panel-body">
				<table class="table table-bordered table-hover table-condensed table-export mt-md">
					<thead>
						<tr>
							<th><?=translate('sl')?></th>
							<?php if (is_superadmin_loggedin() ): ?>
							<th><?=translate('branch')?></th>
							<?php endif; ?>
							<th><?=translate('exam_name')?></th>
							<th><?=translate('action')?></th>
						</tr>
					</thead>
					<tbody>
					<?php $count = 1; foreach($examlist as $row): ?>
						<tr>
							<td><?php echo $count++;?></td>
							<?php if (is_superadmin_loggedin() ): ?>
							<td><?php echo $row['branch_name'];?></td>
							<?php endif; ?>
							<td><?php echo $this->application_model->exam_name_by_id($row['exam_id']);?></td>
							<td>
								<!-- view link -->
								<a href="javascript:void(0);" class="btn btn-circle btn-default icon" onclick="getExamTimetableM('<?=$row['exam_id']?>','<?=$row['class_id']?>','<?=$row['section_id']?>');"> 
									<i class="far fa-eye"></i> 
								</a>

								<?php if (get_permission('exam_timetable', 'is_delete')): ?>
								<!-- delete link -->
								<?php echo btn_delete('timetable/exam_delete/' . $row['exam_id'] .'/'.  $row['class_id'] .'/'.  $row['section_id']);?>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</section>
	<?php endif; ?>
	</div>
</div>
<div class="zoom-anim-dialog modal-block modal-block-lg mfp-hide" id="modal">
	<section class="panel" id='quick_view'></section>
</div>