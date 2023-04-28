<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-users"></i> <?=translate('teachers_list')?></h4>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-hover table-condensed mb-none table-export">
				<thead>
					<tr>
						<th><?php echo translate('sl'); ?></th>
						<th><?php echo translate('photo'); ?></th>
						<th><?php echo translate('name'); ?></th>
						<th><?php echo translate('staff_id'); ?></th>
						<th><?php echo translate('designation'); ?></th>
						<th><?php echo translate('department'); ?></th>
						<th><?php echo translate('email'); ?></th>
						<th><?php echo translate('mobile_no'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
					$branch_id = get_loggedin_branch_id();
					$employees = $this->userrole_model->getTeachersList($branch_id);
					foreach ($employees as $row):
					?>
					<tr>
						<td><?php echo $i++; ?></td>
						<td class="center">
							<img class="rounded" src="<?php echo get_image_url('staff', $row->photo); ?>" width="40" height="40" />
						</td>
						<td><?php echo $row->name; ?></td>
						<td><?php echo $row->staff_id; ?></td>
						<td><?php echo $row->designation_name; ?></td>
						<td><?php echo $row->department_name; ?></td>
						<td><?php echo $row->email; ?></td>
						<td><?php echo $row->mobileno; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
</section>