<?php if (!empty($query)):?>
	<section class="panel appear-animation" data-appear-animation="<?=$global_config['animations'] ?>" data-appear-animation-delay="100">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-user-graduate"></i> <?php echo translate('student_list');?></h4>
		</header>
		<div class="panel-body mb-md">
			<table class="table table-bordered table-condensed table-hover table-export">
				<thead>
					<tr>
						<th><?=translate('sl')?></th>
						<th width="80"><?=translate('photo')?></th>
					<?php if (is_superadmin_loggedin()) { ?>
						<th><?=translate('branch')?></th>
					<?php } ?>
						<th><?=translate('name')?></th>
						<th><?=translate('register_no')?></th>
						<th><?=translate('roll')?></th>
						<th><?=translate('age')?></th>
						<th><?=translate('guardian_name')?></th>
						<th><?=translate('class')?></th>
						<th><?=translate('section')?></th>
						<th><?=translate('email')?></th>
						<th><?=translate('action')?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$count = 1;
					$students = $query->getResult();
					foreach($students as $row):
					?>
					<tr>
						<td class="center"><?php echo $count++; ?></td>
						<td class="center"><img class="rounded" src="<?=get_image_url('student', $row->photo)?>" width="40" height="40"/></td>
					<?php if (is_superadmin_loggedin()) { ?>
						<td><?php echo get_type_name_by_id('branch', $row->branch_id);?></td>
					<?php } ?>
						<td><?php echo $row->first_name .' '.$row->last_name;?></td>
						<td><?php echo $row->register_no;?></td>
						<td><?php echo $row->roll;?></td>
						<td>
							<?php
							if(!empty($row->birthday)){
								$birthday 	= new DateTime($row->birthday);
								$today   	= new DateTime('today');
								$age 		= $birthday->diff($today)->y;
								echo esc($age);
							}else{
								echo "N/A";
							}
							?>
						</td>
						<td><?php echo !empty($row->parent_name) ? $row->parent_name : 'N/A';?></td>
						<td><?php echo $row->class_name;?></td>
						<td><?php echo $row->section_name;?></td>
						<td><?php echo $row->email;?></td>
						<td class="min-w-c">
						<?php if (get_permission('student', 'is_edit')): ?>
							<!-- update link -->
							<a href="<?php echo base_url('student/profile/' . $row->student_id);?>" class="btn btn-default icon btn-circle" data-toggle="tooltip" data-original-title="<?=translate('details')?>">
								<i class="far fa-arrow-alt-circle-right"></i>
							</a>
						<?php endif; if (get_permission('student', 'is_delete')): ?>
							<!-- delete link -->
							<?php echo btn_delete('student/delete_data/' . $row->id . '/' . $row->student_id);?>
						<?php endif; ?>
						</td>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</section>
	<?php endif;?>