<?php

use App\Libraries\App_lib;
$this->app_lib = new App_lib();

$this->validation = \Config\Services::validation();

?>




<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="<?php echo (!isset($validation_error) ? 'active' : ''); ?>">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('role') . " " . translate('list'); ?></a>
			</li>
			<li class="<?php echo (isset($validation_error) ? 'active' : ''); ?>">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('create') . " " . translate('role'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="list" class="tab-pane <?php echo (!isset($validation_error) ? 'active' : ''); ?>">
				<div class="mb-md">
					<table class="table table-bordered table-hover table-condensed table_default">
						<thead>
							<tr>
								<th><?php echo translate('sl'); ?></th>
								<th><?php echo translate('role') . " " . translate('name'); ?></th>
								<th><?php echo translate('system_role'); ?></th>
								<th><?php echo translate('action'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if(count($roles)){ $count = 1; foreach($roles as $row): ?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><?php echo $row['name']; ?></td>
								<td><?php echo $row['is_system'] ? translate('yes') :  translate('no'); ?></td>
								<td class="min-w-xs">
									<a class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?php echo translate('edit'); ?>" href="<?php echo base_url('role/edit/' . $row['id']); ?>"><i class="fas fa-pen-nib"></i></a>
									<a class="btn btn-default btn-circle" href="<?php echo base_url('role/permission/' . $row['id']); ?>"><i class="fab fa-buromobelexperte"></i> <?php echo translate('permission'); ?></a>
									<?php if(!$row['is_system']){ ?>
										<?php echo btn_delete('role/delete/' . $row['id']); ?>
									<?php } ?>
								</td>
							</tr>
							<?php endforeach; }?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="tab-pane <?php echo (isset($validation_error) ? 'active' : ''); ?>" id="create">
				<?php echo form_open(uri_string(), array('class' => 'form-horizontal')); ?>
					<div class="form-group <?php if ($this->validation->getError('role')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('role') . " " . translate('name'); ?> <span class="required">*</span></label>
						<div class="col-md-6 mb-sm">
							<input type="text" class="form-control" name="role" value="<?php echo set_value('role'); ?>">
							<span class="error"><?php echo $this->validation->getError('role'); ?></span>
						</div>
					</div>
					
					<footer class="panel-footer mt-lg">
						<div class="row">
							<div class="col-md-2 col-md-offset-3">
								<button type="submit" name="save" value="1" class="btn btn-default btn-block"><i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?></button>
							</div>
						</div>	
					</footer>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>