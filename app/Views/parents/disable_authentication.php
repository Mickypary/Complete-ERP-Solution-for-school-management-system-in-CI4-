<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<?php echo form_open('parents/disable_authentication', array('class' => 'validate')); ?>
			<header class="panel-heading">
				<h4 class="panel-title">
					<i class="fas fa-users"></i> <?=translate('parents_list')?>
				</h4>
			</header>
			<div class="panel-body">
				<table class="table table-bordered table-condensed table-hover table-export">
					<thead>
						<tr>
							<th width="40px">
								<div class="checkbox-replace">
									<label class="i-checks"><input type="checkbox" id="selectAllchkbox"><i></i></label>
								</div>
							</th>
							<th><?=translate('guardian_name')?></th>
							<th><?=translate('occupation')?></th>
							<th><?=translate('mobile_no')?></th>
							<th><?=translate('email')?></th>
							<th><?=translate('action')?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($parentslist as $parent): ?>	
						<tr>
							<td class="checked-area">
								<div class="checkbox-replace">
									<label class="i-checks"><input type="checkbox" class="user_checkbox" name="views_bulk_operations[]" value="<?=esc($parent->id)?>"><i></i></label>
								</div>
							</td>
							<td><?php echo esc($parent->name);?></td>
							<td><?php echo esc($parent->occupation);?></td>
							<td><?php echo esc($parent->mobileno);?></td>
							<td><?php echo esc($parent->email);?></td>
							<td>
								<!-- update link -->
								<a href="<?php echo base_url('parents/profile/'.$parent->id);?>" class="btn btn-circle btn-default"><i class="fas fa-user-alt"></i> <?=translate('profile')?></a>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		<?php if(get_permission('parent_disable_authentication', 'is_add')): ?>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-offset-10 col-md-2">
						<button type="submit" name="auth" value="1" class="btn btn-default btn-block"> <i class="fas fa-unlock-alt"></i> <?=translate('authentication_activate')?></button>
					</div>
				</div>
			</footer>
		<?php endif; ?>
			<?php echo form_close();?>
		</section>
	</div>
</div>