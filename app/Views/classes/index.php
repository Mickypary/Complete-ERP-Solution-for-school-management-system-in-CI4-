<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();
$this->db = \Config\Database::connect();



?>

<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="<?=base_url('classes')?>"><i class="fas fa-graduation-cap"></i> <?=translate('class')?></a>
			</li>
<?php if (get_permission('section', 'is_view')): ?>
			<li>
				<a href="<?=base_url('sections')?>"><i class="fas fa-award"></i> <?=translate('section')?></a>
			</li>
<?php endif; ?>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active">
				<div class="row">
<?php if (get_permission('classes', 'is_add')): ?>
					<div class="col-md-5 pr-xs">
						<section class="panel panel-custom">
							<div class="panel-heading panel-heading-custom">
								<h4 class="panel-title"><i class="far fa-edit"></i> <?=translate('create_class')?></h4>
							</div>
							<?php echo form_open(uri_string(), array('class' => 'frm-submit'));?>
							<div class="panel-body panel-body-custom">
								<?php if (is_superadmin_loggedin()): ?>
									<div class="form-group">
										<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
										<?php
											$arrayBranch = $this->app_lib->getSelectList('branch');
											echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' data-width='100%'
											onchange='getSectionByBranch(this.value)' data-plugin-selectTwo  data-minimum-results-for-search='Infinity'");
										?>
										<span class="error"></span>
									</div>
								<?php endif; ?>
								<div class="form-group">
									<label class="control-label"><?=translate('name')?> <span class="required">*</span></label>
									<input type="text" class="form-control" name="name" value="" />
									<span class="error"></span>
								</div>
								<div class="form-group">
									<label class="control-label"><?=translate('class_numeric')?></label>
									<input type="text" class="form-control" name="name_numeric" value="" />
									<span class="error"></span>
								</div>
								<div class="form-group">
									<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
									<?php
										$arraySection = array();
										if (!is_superadmin_loggedin()){
											$result = $this->db->table('section')->where('branch_id',get_loggedin_branch_id())->get()->getResult();
											foreach ($result as $row) {
												$arraySection[$row->id] = $row->name;
											}
										}
										echo form_dropdown("sections[]", $arraySection, set_value('sections[]'), "class='form-control mb-sm' id='section_id'
										data-plugin-selectTwo data-width='100%' multiple data-plugin-options='{" . '"placeholder" : "' . translate('select_branch_first') . '" ' ."}'");
									?>
									<span class="error"></span>
								</div>
							</div>
							<footer class="panel-footer panel-footer-custom">
								<div class="text-right">
					                <button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
					                    <i class="fas fa-plus-circle"></i> <?=translate('save')?>
					                </button>
								</div>
							</footer>
							<?php echo form_close();?>
						</section>
					</div>
<?php endif; ?>
					<div class="col-md-<?php if (get_permission('classes', 'is_add')){ echo "7 pl-xs"; }else{ echo "12"; } ?>">
						<section class="panel panel-custom">
							<header class="panel-heading panel-heading-custom">
								<h4 class="panel-title"><i class="fas fa-list-ul"></i> <?=translate('class_list')?></h4>
							</header>
							<div class="panel-body panel-body-custom">
								<div class="table-responsive">
									<table class="table table-bordered table-hover table-condensed tbr-top mb-none">
										<thead>
											<tr>
												<th>#</th>
												<th><?=translate('branch')?></th>
												<th><?=translate('class_name')?></th>
												<th><?=translate('class_numeric')?></th>
												<th><?=translate('section')?></th>
												<th><?=translate('action')?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$count = 1;
											if (count($classlist)){
												foreach($classlist as $row):
											?>
											<tr>
												<td><?php echo $count++;?></td>
												<td><?php echo $row['branch_name'];?></td>
												<td><?php echo $row['name'];?></td>
												<td><?php echo $row['name_numeric'];?></td>
												<td>
													<?php
													$sections = $this->db->table('sections_allocation')->getWhere(array('class_id' => $row['id']))->getResult();
													foreach ($sections as $section) {
														echo get_type_name_by_id('section', $section->section_id). "<br>";
													}
													?>
												</td>
												<td>
												<?php if (get_permission('classes', 'is_edit')): ?>
													<!--update link-->
													<a href="<?php echo base_url('classes/edit/' . $row['id']);?>" class="btn btn-default btn-circle icon">
														<i class="fas fa-pen-nib"></i>
													</a>
												<?php endif; if (get_permission('classes', 'is_delete')): ?>
													<!--delete link-->
													<?php echo btn_delete('classes/delete/' . $row['id']);?>
												<?php endif; ?>
												</td>
											</tr>
										<?php
											endforeach;
										}else{
											echo '<tr><td colspan="6"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
										}
										?>
										</tbody>
									</table>
								</div>
							</div>
						</section>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>