<?php

use App\Libraries\App_lib;
$this->app_lib = new App_lib();

$this->validation = \Config\Services::validation();

?>




<div class="row">
<?php if (get_permission('department', 'is_add')): ?>
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('add') . " " . translate('department'); ?></h4>
			</header>
			<?php echo form_open(uri_string()); ?>
				<div class="panel-body">
				<?php if (is_superadmin_loggedin()): ?>
					<div class="form-group">
						<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"><?=$this->validation->getError('branch_id')?></span>
					</div>
				<?php endif; ?>
					<div class="form-group mb-md">
						<label class="control-label"><?php echo translate('department_name'); ?> <span class="required">*</span></label>
						<input type="text" class="form-control" name="department_name" value="<?php echo set_value('department_name'); ?>" />
						<span class="error"><?=$this->validation->getError('department_name')?></span>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-12">
							<button class="btn btn-default pull-right" type="submit"><i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?></button>
						</div>	
					</div>
				</div>
			<?php echo form_close(); ?>
		</section>
	</div>
<?php endif; ?>
<?php if (get_permission('department', 'is_view')): ?>
	<div class="col-md-<?php if (get_permission('department', 'is_add')){ echo "7"; }else{echo "12";} ?>">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-list-ul"></i> <?php echo translate('department') . " " . translate('list'); ?></h4>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none">
						<thead>
							<tr>
								<th><?php echo translate('sl'); ?></th>
								<th><?=translate('branch')?></th>
								<th><?php echo translate('name'); ?></th>
								<th><?php echo translate('action'); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php
						$count = 1;
						if (count($department)) {
							foreach ($department as $row):
						?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><?php echo $row['branch_name']; ?></td>
								<td><?php echo $row['name']; ?></td>
								<td class="min-w-xs">
								<?php if (get_permission('department', 'is_edit')): ?>
									<a class="btn btn-default btn-circle icon" href="javascript:void(0);" onclick="getDepartmentDetails('<?=$row['id']?>')">
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php  endif; if (get_permission('department', 'is_delete')): ?>
									<?php echo btn_delete('employee/department_delete/' . $row['id']); ?>
								<?php endif; ?>
								</td>
							</tr>
						<?php
							endforeach;
						}else{
								echo '<tr><td colspan="4"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
						}
						?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>
<?php endif; ?>
<?php if (get_permission('department', 'is_edit')): ?>
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('department'); ?></h4>
		</header>
		<?php echo form_open('employee/department_edit', array('class' => 'frm-submit')); ?>
			<div class="panel-body">
				<input type="hidden" name="department_id" id="edepartment_id" value="" />
			<?php if (is_superadmin_loggedin()): ?>
				<div class="form-group mb-md">
					<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
					<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='ebranch_id'
						data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
					<span class="error"></span>
				</div>
			<?php endif; ?>
				<div class="form-group mb-md">
					<label class="control-label"><?php echo translate('name'); ?> <span class="required">*</span></label>
					<input type="text" class="form-control" value="" name="department_name" id="cdepartment_name" />
					<span class="error"></span>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
							<i class="fas fa-plus-circle"></i> <?php echo translate('update'); ?>
						</button>
						<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
					</div>
				</div>
			</footer>
		<?php echo form_close(); ?>
	</section>
</div>
<?php endif; ?>