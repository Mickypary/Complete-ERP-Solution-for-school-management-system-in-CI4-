<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();
$this->db = \Config\Database::connect();


?>



<?php $widget = (is_superadmin_loggedin() ? 4 : 6); ?>
<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><?php echo translate('select_ground'); ?></h4>
			</header>
			<?php echo form_open(uri_string(), array('class' => 'validate'));?>
			<div class="panel-body">
				<div class="row mb-sm">
				<?php if (is_superadmin_loggedin() ): ?>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' onchange='getClassByBranch(this.value)'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
						</div>
					</div>
				<?php endif; ?>
					<div class="col-md-<?php echo $widget; ?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
							<?php
								$arrayClass = $this->app_lib->getClass($branch_id);
								echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)'
								required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="col-md-<?php echo $widget; ?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
							<?php
								$arraySection = $this->app_lib->getSections(set_value('class_id'));
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
						<button type="submit" name="submit" value="search" class="btn btn-default btn-block"> <i class="fas fa-filter"></i> <?=translate('filter')?></button>
					</div>
				</div>
			</footer>
		<?php echo form_close();?>
		</section>

		<?php if (isset($students)):?>
			<section class="panel" >
			<?php echo form_open('student/transfersave', array('class' => 'frm-submit'));?>
				<header class="panel-heading">
					<h4 class="panel-title"><?=translate('the_next_session_was_transferred_to_the_students')?></h4>
				</header>
				<div class="panel-body">
					<div class="row mb-lg">
						<div class="col-md-12">
							<div class="alert alert-subl mt-md mb-lg">
								<strong>Instructions :</strong><br/>
								1. The Roll field shows the previous roll and you can manually add new roll for promoted session.<br/>
								2. Roll number is unique, so carefully enter the roll number. Automatically generate a roll when your entered roll exists.<br/>
								3. For Enroll, You can select "Running Class" and "Promote To Class" same (Student will change in Session year but classes remain unchanged).<br/>
								4. Please double check and Fill-up all fields carefully Then click  Promotion button.
							</div>
						</div>
						<div class="col-md-12 mb-md">
							<div class="checkbox-replace">
								<label class="i-checks"><input type="checkbox" name="due_forward" checked><i></i>Carry Forward Due in Next Session</label>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label"><?=translate('promote_to_session')?> <span class="required">*</span></label>
								<?php
									$arraySession = array("" => translate('select'));
									$years = $this->db->table('schoolyear')->get()->getResult();
									foreach ($years as $year){
										$arraySession[$year->id] = $year->school_year;
									}
									echo form_dropdown("promote_session_id", $arraySession, set_value('promote_session_id'), "class='form-control' id='session_id'
									data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"></span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label"><?=translate('promote_to_class')?> <span class="required">*</span></label>
								<?php
									$arrayClass = $this->app_lib->getClass($branch_id);
									echo form_dropdown("promote_class_id", $arrayClass, set_value('promote_class_id'), "class='form-control' id='class_promote_id'
									data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
								?>
								<span class="error"></span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label"><?=translate('promote_to_section')?> <span class="required">*</span></label>
								<?php
									$arraySection = array("" => translate('select'));
									echo form_dropdown("promote_section_id", $arraySection, set_value('promote_section_id'), "class='form-control' id='section_promote_id'
									data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
								?>
								<span class="error"></span>
							</div>
						</div>
					</div>
					<div class="table-responsive mb-md">
						<table class="table table-condensed table-hover table-bordered tbr-top">
							<thead>
								<tr>
									<th width="50">#</th>
									<th><?=translate('student_name')?></th>
									<th><?=translate('register_no')?></th>
									<th><?=translate('guardian_name')?></th>
									<th><?=translate('mark_summary')?></th>
									<th><?=translate('roll')?></th>
									<th class="center">
										<div class="checkbox-replace">
											<label class="i-checks" data-toggle="tooltip" data-original-title="Promotion"><input type="checkbox" id="selectAllchkbox" checked><i></i></label>
										</div>				
									</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$count = 1;
								if (count($students)) {
									foreach($students as $key => $row):
								?>
								<tr>
									<input type="hidden" name="promote[<?=$key?>][student_id]" value="<?=$row['student_id']?>" />
									<td><?php echo $count++;?></td>
									<td><?php echo $row['fullname'];?></td>
									<td><?php echo $row['register_no'];?></td>
									<td><?php echo (!empty($row['parent_id']) ? get_type_name_by_id('parent', $row['parent_id']) : 'N/A');?></td>
									<td >
										<a target="_blank" href="<?php echo base_url('student/profile/' . $row['student_id']);?>" class="btn btn-default btn-circle">
											<i class="fas fa-eye"></i> <?=translate('view')?>
										</a>
									</td>
									<td>
										<div class="form-group">
										<input type="number" class="form-control" name="promote[<?=$key?>][roll]" value="<?=$row['roll']?>" />
										<span class="error"></span>
										</div>
									</td>
									<td  class="center checked-area">
										<div class="pt-csm checkbox-replace">
											<label class="i-checks"><input type="checkbox" checked name="promote[<?=$key?>][enroll_id]" value="<?=$row['id']?>" ><i></i></label>
										</div>
									</td>
								</tr>
								<?php
									endforeach;
								} else {
									echo '<tr><td colspan="7"><h5 class="text-danger text-center">'.translate('no_information_available').'</td></tr>';
								}
							?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-offset-10 col-md-2">
							<button type="submit" name="submit" value="promote" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
								<i class="fab fa-nintendo-switch"></i> <?=translate('promotion')?>
							</button>
						</div>
					</div>
				</div>
				<?php
				echo form_hidden(array(
					'branch_id' 	=> $branch_id,
					'class_id' 		=> $class_id,
					'section_id' 	=> $section_id
				));
				echo form_close();
				?>
			</section>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		 $('#class_promote_id').on('change', function() {
			var classID = $(this).val();
			$.ajax({
				url: "<?=base_url('ajax/getSectionByClass')?>",
				type: 'POST',
				data:{
					class_id: classID
				},
				success: function (data){
					$('#section_promote_id').html(data);
				}
			});
		});
	});
</script>