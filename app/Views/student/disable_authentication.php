<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();


?>




<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><?=translate('select_ground')?></h4>
			</header>
			<?php echo form_open('student/disable_authentication', array('class' => 'validate')); ?>
			<div class="panel-body">
				<div class="row mb-sm">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' onchange='getClassByBranch(this.value)' required
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
						</div>
					</div>
					<div class="col-md-4 mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
							<?php
								$arrayClass = $this->app_lib->getClass($branch_id);
								echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' required onchange='getSectionByClass(this.value,1)'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="col-md-4 mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
							<?php
								$arraySection = $this->app_lib->getSections(set_value('class_id'), true);
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

		<?php if (isset($students)):?>
		<section class="panel appear-animation" data-appear-animation="<?php echo $global_config['animations'];?>" data-appear-animation-delay="100">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-user-graduate"></i> <?php echo translate('student_list');?></h4>
			</header>
			<?php echo form_open(uri_string(), array('class' => 'validate')); ?>
			<div class="panel-body mb-md">
				<table class="table table-bordered table-condensed table-hover table-export">
					<thead>
						<tr>
							<th width="40px">
								<div class="checkbox-replace">
									<label class="i-checks"><input type="checkbox" id="selectAllchkbox"><i></i></label>
								</div>
							</th>
							<th width="80"><?=translate('photo')?></th>
							<th><?=translate('name')?></th>
							<th><?=translate('register_no')?></th>
							<th><?=translate('roll')?></th>
							<th><?=translate('guardian_name')?></th>
							<th><?=translate('class')?></th>
							<th><?=translate('section')?></th>
							<th><?=translate('email')?></th>
							<th><?=translate('mobile_no')?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($students as $row):
						?>
						<tr>
							<td class="checked-area">
								<div class="checkbox-replace">
									<label class="i-checks"><input type="checkbox" name="views_bulk_operations[]" value="<?=$row['student_id']?>"><i></i></label>
								</div>
							</td>
							<td class="center"><img class="rounded" src="<?php echo get_image_url('student', $row['photo']); ?>" width="40" height="40"/></td>
							<td><?php echo $row['fullname'];?></td>
							<td><?php echo $row['register_no'];?></td>
							<td><?php echo $row['roll'];?></td>
							<td><?php echo (!empty($row['parent_id']) ? get_type_name_by_id('parent', $row['parent_id']) : 'N/A');?></td>
							<td><?php echo $row['class_name'];?></td>
							<td><?php echo $row['section_name'];?></td>
							<td><?php echo $row['email'];?></td>
							<td><?php echo $row['mobileno'];?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		<?php if(get_permission('student_disable_authentication', 'is_add')): ?>
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
