<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();


?>




<div class="row">
<?php if (get_permission('assign_class_teacher', 'is_add')): ?>
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-edit"></i> <?=translate('class_teacher_allocation')?></h4>
			</header>
			<?php echo form_open('classes/teacher_allocation_save', array('class' => 'frm-submit'));?>
				<div class="panel-body">
					<?php if (is_superadmin_loggedin()): ?>
					<div class="form-group">
						<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id'
							data-width='100%' data-plugin-selectTwo data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"></span>
					</div>
					<?php endif; ?>
					<div class="form-group">
						<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
						<?php
							$arrayClass = $this->app_lib->getClass($branch_id);
							echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
						?>
						<span class="error"></span>
					</div>
					<div class="form-group">
						<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
						<?php
							$arraySection = $this->app_lib->getSections(set_value('class_id'));
							echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id' 
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
						?>
						<span class="error"></span>
					</div>
					<div class="form-group mb-md">
						<label class="control-label"><?=translate('class_teacher')?> <span class="required">*</span></label>
						<?php
							$arrayTeacher = $this->app_lib->getStaffList($branch_id, 3);
							echo form_dropdown("staff_id", $arrayTeacher, set_value('staff_id'), "class='form-control' id='staff_id'
							data-plugin-selectTwo data-width='100%' ");
						?>
						<span class="error"></span>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-12">
			                <button type="submit" name="save" value="1" class="btn btn-default pull-right" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
			                    <i class="fas fa-plus-circle"></i> <?=translate('save') ?>
			                </button>
						</div>	
					</div>
				</div>
			<?php echo form_close();?>
		</section>
	</div>
<?php endif; ?>
	<div class="col-md-<?php if (get_permission('assign_class_teacher', 'is_add')){ echo "7"; }else{ echo "12"; } ?>">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-list-ul"></i> <?=translate('class_teacher') . " " . translate('list')?></h4>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none">
						<thead>
							<tr>
							    <th>#</th>
								<th><?=translate('branch')?></th>
								<th><?=translate('class_teacher')?></th>
								<th><?=translate('class')?></th>
								<th><?=translate('section')?></th>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							if ($query->getNumRows() > 0){
								$count = 1;
								$allocations = $query->getResult();
								foreach ($allocations as $allocation):
							?>
							<tr>
							    <td><?php echo $count++;?></td>
								<td><?php echo get_type_name_by_id('branch', $allocation->branch_id);?></td>
								<td><?php echo $allocation->teacher_name;?></td>
								<td><?php echo $allocation->class_name;?></td>
								<td><?php echo $allocation->section_name;?></td>
								<td>
								<?php if (get_permission('assign_class_teacher', 'is_edit')): ?>
									<!-- update link -->
									<a href="javascript:void(0);" class="btn btn-circle btn-default icon" onclick="getAllocationTeacher('<?=$allocation->id?>')">
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; ?>
								<?php if (get_permission('assign_class_teacher', 'is_delete')): ?>
									<!-- delete link -->
									<?=btn_delete('classes/teacher_allocation_delete/' . $allocation->id)?>
								<?php endif; ?>
								</td>
							</tr>
							<?php
								endforeach; 
							}else {
								echo '<tr><td colspan="6"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>

<?php if (get_permission('assign_class_teacher', 'is_edit')): ?>
<!-- teacher allocation edit modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<?php echo form_open('classes/teacher_allocation_save', array('class' => 'frm-submit'));?>
	<section class="panel" id='allocation'></section>
	<?php echo form_close(); ?>
</div>
<?php endif; ?>

<script type="text/javascript">
	$(document).ready(function () {
		$(document).on('change', '#branch_id', function(e) {	
			var branchID = $(this).val();
			getClassByBranch(branchID);
			getStaffListRole(branchID, 3);
		});
	});

	// get leave approvel details
	function getAllocationTeacher(id) {
	    $.ajax({
	        url: base_url + 'classes/getAllocationTeacher',
	        type: 'POST',
	        data: {'id': id},
	        dataType: "html",
	        success: function (data) {
				$('#allocation').html(data);
				mfp_modal('#modal');
	        },
			complete: function () {
				$('.selecttwo').select2({
					theme: 'bootstrap',
					width: '100%',
					minimumResultsForSearch: 'Infinity'
				});
			}
	    });
	}
</script>