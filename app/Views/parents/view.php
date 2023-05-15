<?php
use App\Libraries\App_lib;
use App\Models\ParentsModel;

$this->app_lib = new App_lib();
$this->parents_model = new ParentsModel();


?>




<?php if (is_superadmin_loggedin() ): ?>
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><?=translate('select_ground')?></h4>
		</header>
		<?php echo form_open(uri_string(), array('class' => 'validate'));?>
		<div class="panel-body">
			<div class="row mb-sm">
				<div class="col-md-offset-3 col-md-6">
					<div class="form-group">
						<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
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
<?php endif; ?>
<?php if (!empty($branch_id)): ?>
<?php if (is_superadmin_loggedin()) { ?>
<div class="row appear-animation" data-appear-animation="<?=$global_config['animations'] ?>" data-appear-animation-delay="100">
<?php } else { ?>
<div class="row">
<?php } ?>	
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title">
					<i class="fas fa-users"></i> <?=translate('parents_list')?>
				</h4>
			</header>
			<div class="panel-body">
				<div class="mb-md mt-md">
					<table class="table table-bordered table-hover table-condensed mb-none table-export">
						<thead>
							<tr>
								<th><?=translate('sl')?></th>
							<?php if (is_superadmin_loggedin()) { ?>
								<th><?=translate('branch')?></th>
							<?php } ?>
								<th><?=translate('guardian_name')?></th>
								<th><?=translate('occupation')?></th>
								<th><?=translate('mobile_no')?></th>
								<th><?=translate('email')?></th>
							<?php
							$show_custom_fields = custom_form_table('parents', $branch_id);
							if (count($show_custom_fields)) {
								foreach ($show_custom_fields as $fields) {
							?>
								<th><?=$fields['field_label']?></th>
							<?php } } ?>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							$parentslist = $this->parents_model->getParentList($branch_id);
							if (count($parentslist)) {
								foreach($parentslist as $row):
							?>	
							<tr>
								<td><?php echo $count++; ?></td>
							<?php if (is_superadmin_loggedin()) { ?>
								<td><?php echo get_type_name_by_id('branch', $row->branch_id);?></td>
							<?php } ?>
								<td><?php echo $row->name;?></td>
								<td><?php echo $row->occupation;?></td>
								<td><?php echo $row->mobileno;?></td>
								<td><?php echo $row->email;?></td>
							<?php
							if (count($show_custom_fields)) {
								foreach ($show_custom_fields as $fields) {
							?>
								<td><?php echo get_table_custom_field_value($fields['id'], $row->id);?></td>
							<?php } } ?>
								<td class="min-w-xs">
								<?php if (get_permission('parent', 'is_edit')): ?>
									<!-- update link -->
									<a href="<?php echo base_url('parents/profile/' . $row->id);?>" class="btn btn-default btn-circle icon" data-toggle="tooltip"
									data-original-title="<?=translate('profile')?>">
										<i class="far fa-arrow-alt-circle-right"></i>
									</a>
								<?php endif; if (get_permission('parent', 'is_delete')): ?>
									<!-- delete link -->
									<?php echo btn_delete('parents/delete/' . $row->id);?>
								<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; };?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>
<?php endif; ?>