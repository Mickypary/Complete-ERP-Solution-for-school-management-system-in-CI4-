<?php

use App\Models\RoleModel;

$this->role_model = new RoleModel();


?>





<section class="panel">
	<div class="panel-heading">
		<h4 class="panel-title"> <i class="fab fa-buromobelexperte"></i> <?php echo translate('role_permission_for') . " : " . get_type_name_by_id('roles', $role_id); ?></h4>
	</div>
    <?php echo form_open_multipart(uri_string()); ?>
		<input type="hidden" name="role_id" value="<?php echo $role_id; ?>">
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-condensed mt-sm" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><?php echo translate('feature'); ?></th>
							<th>
								<div class="checkbox-replace"> 
									<label class="i-checks"><input type="checkbox" id="all_view" value="1"><i></i> <?php echo translate('view'); ?></label> 
								</div>
							</th>
							<th>
								<div class="checkbox-replace"> 
									<label class="i-checks"><input type="checkbox" id="all_add" value="1"><i></i> <?php echo translate('add'); ?></label> 
								</div>
							</th>
							<th>
								<div class="checkbox-replace"> 
									<label class="i-checks"><input type="checkbox" id="all_edit" value="1"><i></i> <?php echo translate('edit'); ?></label> 
								</div>
							</th>
							<th>
								<div class="checkbox-replace"> 
									<label class="i-checks"><input type="checkbox" id="all_delete" value="1"><i></i> <?php echo translate('delete'); ?></label> 
								</div>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if(count($modules)){ 
							foreach($modules as $module):
							?>
						<tr>
							<!-- <th colspan="5"><?php //echo $module['id']; ?></th> -->
							<th colspan="5"><?php echo $module['name']; ?></th>
						</tr>
						<?php
						$permissions = $this->role_model->check_permissions($module['id'], $role_id);
						foreach($permissions as $permission):
						?>
						<input type="hidden" name="privileges[<?php echo $permission['id']; ?>][privileges_id]" value="<?php echo $permission['id']; ?>">
						<tr>
							<td class="pl-xl"><i class="far fa-arrow-alt-circle-right text-md"></i> <?php echo $permission['name']; ?></td>
							<td>
								<?php if($permission['show_view']){ ?>
								<div class="checkbox-replace"> 
									<label class="i-checks"><input type="checkbox" class="cb_view" name="privileges[<?php echo $permission['id']; ?>][view]" <?php echo ($permission['is_view'] == 1 ? 'checked' : '');?> value="1" >
										<i></i>
									</label>
								</div>
								<?php } ?>
							</td>
							<td>
								<?php if($permission['show_add']){ ?>
								<div class="checkbox-replace"> 
									<label class="i-checks"><input type="checkbox" class="cb_add" name="privileges[<?php echo $permission['id']; ?>][add]" <?php echo ($permission['is_add'] == 1 ? 'checked' : '');?> value="1" >
										<i></i>
									</label>
								</div>
								<?php } ?>
							</td>
							<td>
								<?php if($permission['show_edit']){ ?>
								<div class="checkbox-replace"> 
									<label class="i-checks"><input type="checkbox" class="cb_edit" name="privileges[<?php echo $permission['id']; ?>][edit]" <?php echo ($permission['is_edit'] == 1 ? 'checked' : '');?> value="1" >
										<i></i>
									</label>
								</div>
								<?php } ?>
							</td>
							<td>
								<?php if($permission['show_delete']){ ?>
								<div class="checkbox-replace"> 
									<label class="i-checks"><input type="checkbox" class="cb_delete" name="privileges[<?php echo $permission['id']; ?>][delete]" <?php echo ($permission['is_delete'] == 1 ? 'checked' : '');?> value="1" >
										<i></i>
									</label>
								</div>
								<?php } ?>
							</td>
						</tr>
						<?php endforeach; ?>
						<?php endforeach; }?>
					</tbody>
				</table>	
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-offset-9 col-md-3">
					<button type="submit" name="save" value="1" class="btn btn-default btn-block"><i class="fas fa-edit"></i> <?php echo translate('update'); ?></button>
				</div>
			</div>
		</footer>
	<?php echo form_close(); ?>
</section>