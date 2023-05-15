<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();
$this->validation = \Config\Services::validation();
$this->db = \Config\Database::connect();



?>




<div class="row">
<?php if (get_permission('student_category', 'is_add')): ?>
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('add') . " " . translate('category'); ?></h4>
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
						<label class="control-label"><?php echo translate('category') . " " . translate('name'); ?> <span class="required">*</span></label>
						<input type="text" class="form-control" name="category_name" value="<?php echo set_value('category_name'); ?>" />
						<span class="error"><?php echo $this->validation->getError('category_name'); ?></span>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-12">
							<button class="btn btn-default pull-right" type="submit" name="category" value="1"><i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?></button>
						</div>	
					</div>
				</div>
			<?php echo form_close(); ?>
		</section>
	</div>
<?php endif; ?>
<?php if (get_permission('student_category', 'is_view')): ?>
	<div class="col-md-<?php if (get_permission('student_category', 'is_add')){ echo "7"; }else{ echo "12"; } ?>">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-list-ul"></i> <?php echo translate('category') . " " . translate('list'); ?></h4>
			</header>

			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none">
						<thead>
							<tr>
								<th><?=translate('branch')?></th>
								<th><?=translate('id')?></th>
								<th><?php echo translate('name'); ?></th>
								<th><?php echo translate('action'); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php
						if (!is_superadmin_loggedin()) {
							$this->db->where('branch_id', get_loggedin_branch_id());
						}
						$categorylist = $this->db->table('student_category')->get()->getResultArray();
						if (!empty($categorylist)){
							foreach ($categorylist as $row): 
								?>
							<tr>
								<td><?php echo get_type_name_by_id('branch', $row['branch_id']);?></td>
								<td><?php echo esc($row['id']);?></td>
								<td><?php echo esc($row['name']); ?></td>
								<td class="min-w-xs">
								<?php if (get_permission('student_category', 'is_edit')): ?>
									<!-- update link -->
									<a class="btn btn-default btn-circle icon" href="javascript:void(0);" onclick="getStudentCategory('<?php echo $row['id']; ?>')">
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; if (get_permission('student_category', 'is_delete')): ?>
									<!-- delete link -->
									<?php echo btn_delete('student/category_delete/' . $row['id']); ?>
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
<?php if (get_permission('student_category', 'is_edit')): ?>
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title">
				<i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('category'); ?>
			</h4>
		</header>
		<?php echo form_open('student/category_edit', array('class' => 'frm-submit')); ?>
			<div class="panel-body">
				<input type="hidden" name="category_id" id="ecategory_id" value="">
				<?php if (is_superadmin_loggedin()): ?>
					<div class="form-group">
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
					<label class="control-label"><?php echo translate('category') . " " . translate('name'); ?> <span class="required">*</span></label>
					<input type="text" class="form-control" value="" name="category_name" id="ecategory_name" />
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