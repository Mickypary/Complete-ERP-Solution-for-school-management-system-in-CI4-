<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();


?>




<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?=translate('teacher_assign_list')?></a>
			</li>
<?php if (get_permission('subject_teacher_assign', 'is_add')): ?>
			<li>
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?=translate('assign')?></a>
			</li>
<?php endif; ?>
		</ul>
		<div class="tab-content">
			<div  id="list" class="tab-pane active">
				<table class="table table-bordered table-hover table-condensed mb-none table-export">
					<thead>
						<tr>
							<th>#</th>
						<?php if (is_superadmin_loggedin()) { ?>
							<th><?=translate('branch')?></th>
						<?php } ?>
							<th><?=translate('teacher')?></th>
							<th><?=translate('department')?></th>
							<th><?=translate('class')?></th>
							<th><?=translate('section')?></th>
							<th><?=translate('subject')?></th>
							<th><?=translate('action')?></th>
						</tr>
					</thead>
					<tbody>
						<?php $count = 1; foreach($assignlist as $row): ?>
						<tr>
							<td><?php echo $count++;?></td>
						<?php if (is_superadmin_loggedin()) { ?>
							<td><?php echo get_type_name_by_id('branch', $row->branch_id); ?></td>
						<?php } ?>
							<td><?php echo $row->teacher_name;?></td>
							<td><?php echo $row->department_name;?></td>
							<td><?php echo $row->class_name;?></td>
							<td><?php echo $row->section_name;?></td>
							<td><?php echo $row->subject_name;?></td>
							<td>
							<?php if (get_permission('subject_teacher_assign', 'is_delete')): ?>
								<!-- delete link -->
								<?php echo btn_delete('subject/teacher_assign_delete/' . $row->id);?>
							<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
<?php if (get_permission('subject_teacher_assign', 'is_add')): ?>
			<div class="tab-pane" id="create">
				<?php echo form_open(uri_string(), array('class' => 'form-horizontal form-bordered frm-submit'));?>
					<?php if (is_superadmin_loggedin()): ?>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate('branch')?> <span class="required">*</span></label>
							<div class="col-md-6">
								<?php
									$arrayBranch = $this->app_lib->getSelectList('branch');
									echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id'
									data-width='100%' data-plugin-selectTwo  data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"></span>
							</div>
						</div>
					<?php endif; ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('teacher')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayTeacher = $this->app_lib->getStaffList($branch_id, 3);
								echo form_dropdown("staff_id", $arrayTeacher, set_value('staff_id'), "class='form-control' id='staff_id'
								data-plugin-selectTwo data-width='100%' ");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('class')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayClass = $this->app_lib->getClass($branch_id);
								echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('section')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arraySection = $this->app_lib->getSections(set_value('class_id'));
								echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id' 
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('subject')?> <span class="required">*</span></label>
						<div class="col-md-6 mb-md">
							<?php
								$array = array("" => translate('select'));
								echo form_dropdown("subject_id", $array, set_value('subject_id'), "class='form-control' id='subject_id' 
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
							<span class="error"></span>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?=translate('save')?>
								</button>
							</div>
						</div>
					</footer>
				<?php echo form_close();?>
			</div>
<?php endif; ?>
		</div>
	</div>
</section>
<script type="text/javascript">
	$(document).ready(function () {
		$(document).on('change', '#branch_id', function() {
			var branchID = $(this).val();
			getClassByBranch(branchID);
			getStaffListRole(branchID, 3);
		    $('#teacher_id').html('').append('<option value=""><?=translate("select_department_first")?></option>');
		    $('#subject_id').html('').append('<option value=""><?=translate("select")?></option>');
		});

		$(document).on('change', '#class_id', function() {
			var classID = $(this).val();
			getSectionByClass(classID,0);
		    $('#subject_id').html('').append('<option value=""><?=translate("select")?></option>');
		});
		
		$(document).on('change', '#section_id', function() {
			var sectionID = $(this).val();
			var classID = $('#class_id').val();
			$.ajax({
				url: base_url + 'subject/getByClassSection',
				type: 'POST',
				data: {
					sectionID: sectionID,
					classID: classID
				},
				success: function (data) {
					$('#subject_id').html(data);
				}
			});
		});
	});
</script>