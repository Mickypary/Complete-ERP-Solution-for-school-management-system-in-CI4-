<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();


?>







<?php $widget = (is_superadmin_loggedin() ? 'col-md-6' : 'col-md-offset-3 col-md-6'); ?>
<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><?=translate('select_ground')?></h4>
			</header>
			<?php echo form_open('employee/disable_authentication', array('class' => 'validate'));?>
			<div class="panel-body">
				<div class="row mb-sm">
					<?php if (is_superadmin_loggedin()): ?>
	                <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id' required
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="<?php echo $widget; ?> mb-sm">
                        <div class="form-group">
                            <label class="control-label"><?=translate('role')?> <span class="required">*</span></label>
							<?php
								$role_list = $this->app_lib->getRoles();
								echo form_dropdown("staff_role", $role_list, set_value('staff_role'), "class='form-control' data-plugin-selectTwo required data-width='100%'
								data-minimum-results-for-search='Infinity' ");
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

		<?php if (isset($stafflist)): ?>
		<section class="panel appear-animation" data-appear-animation="<?=$global_config['animations'] ?>" data-appear-animation-delay="100">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-user-graduate"></i> <?php echo translate('student_list');?></h4>
			</header>
			<?php echo form_open('employee/disable_authentication', array('class' => 'validate')); ?>
			<div class="panel-body mb-md">
				<table class="table table-bordered table-hover table-condensed mb-none table-export">
					<thead>
						<tr>
							<th width="40px">
								<div class="checkbox-replace">
									<label class="i-checks"><input type="checkbox" id="selectAllchkbox"><i></i></label>
								</div>
							</th>
							<th width="80"><?php echo translate('photo');?></th>
							<th><?=translate('branch')?></th>
							<th><?=translate('name')?></th>
							<th><?=translate('designation')?></th>
							<th><?=translate('department')?></th>
							<th><?=translate('email')?></th>
							<th><?=translate('mobile_no')?></th>
							<th><?=translate('action')?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($stafflist as $row): ?>
						<tr>
							<td class="checked-area">
								<div class="checkbox-replace">
									<label class="i-checks"><input type="checkbox" name="views_bulk_operations[]" value="<?=esc($row->id)?>"><i></i></label>
								</div>
							</td>
							<td class="center">
								<img class="rounded" src="<?php echo get_image_url('staff', $row->photo); ?>" width="35" height="35" />
							</td>
							<td><?php echo esc(get_type_name_by_id('branch', $row->branch_id));?></td>
							<td><?php echo esc($row->name);?></td>
							<td><?php echo esc($row->designation_name);?></td>
							<td><?php echo esc($row->department_name);?></td>
							<td><?php echo esc($row->email); ?></td>
							<td><?php echo esc($row->mobileno); ?></td>
							<td>
							<?php if (get_permission('employee', 'is_edit')): ?>
								<!-- update link -->
								<a href="<?php echo base_url('employee/profile/'.$row->id); ?>" class="btn btn-circle btn-default">
									<i class="fas fa-user-alt"></i> <?=translate('profile')?>
								</a>
							<?php endif; ?>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
			<?php if(get_permission('employee_disable_authentication', 'is_add')): ?>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-offset-10 col-md-2">
						<button type="submit" name="auth" value="1" class="btn btn-default btn-block"> <i class="fas fa-unlock-alt"></i> <?=translate('authentication_activate')?></button>
					</div>
				</div>
			</footer>
			<?php endif; ?>
			<?php echo form_close(); ?>
		</section>
		<?php endif;?>
	</div>
</div>
