<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();


?>



<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
<?php if (get_permission('classes', 'is_view')): ?>
			<li>
				<a href="<?=base_url('classes')?>"><i class="fas fa-graduation-cap"></i> <?=translate('class')?></a>
			</li>
<?php endif; ?>
			<li class="active">
				<a href="<?=base_url('sections')?>"><i class="fas fa-award"></i> <?=translate('section')?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="sections" class="tab-pane active">
				<div class="row">
<?php if (get_permission('section', 'is_add')): ?>
					<div class="col-md-5 pr-xs">
						<section class="panel panel-custom">
							<?php echo form_open('sections/save', array('class' => 'frm-submit'));?>
								<div class="panel-heading panel-heading-custom">
									<h4 class="panel-title"><i class="far fa-edit"></i> <?=translate('create_section')?></h4>
								</div>
								<div class="panel-body panel-body-custom">
								<?php if (is_superadmin_loggedin()): ?>
									<div class="form-group">
										<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
										<?php
											$arrayBranch = $this->app_lib->getSelectList('branch');
											echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' data-width='100%'
											data-plugin-selectTwo  data-minimum-results-for-search='Infinity'");
										?>
										<span class="error"></span>
									</div>
								<?php endif; ?>
									<div class="form-group">
										<label class="control-label"><?=translate('name')?><span class="required">*</span></label>
										<input type="text" class="form-control" name="name" value="" />
										<span class="error"></span>
									</div>
									<div class="form-group">
										<label class="control-label"><?=translate('capacity')?></label>
										<input type="text" class="form-control" name="capacity" value="" />
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
					<div class="col-md-<?php if (get_permission('section', 'is_add')){ echo "7 pl-xs"; }else{ echo "12"; } ?>">
						<section class="panel panel-custom">
							<header class="panel-heading panel-heading-custom">
								<h4 class="panel-title"><i class="fas fa-list-ul"></i> <?=translate('section_list')?></h4>
							</header>
							<div class="panel-body panel-body-custom">
								<div class="table-responsive">
									<table class="table table-bordered table-hover table-condensed mb-none">
										<thead>
											<tr>
												<th>#</th>
												<th><?=translate('branch')?></th>
												<th><?=translate('section_name')?></th>
												<th><?=translate('capacity ')?></th>
												<th><?=translate('action')?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$count = 1;
											if (count($sectionlist)){
												foreach ($sectionlist as $row):
											?>
											<tr>
												<td><?php echo $count++;?></td>
												<td><?php echo $row['branch_name'];?></td>
												<td><?php echo $row['name'];?></td>
												<td><?php echo $row['capacity'];?></td>
												<td>
												<?php if (get_permission('section', 'is_edit')): ?>
													<!--update link-->
													<a href="<?php echo base_url('sections/edit/' . $row['id']);?>" class="btn btn-default btn-circle icon">
														<i class="fas fa-pen-nib"></i>
													</a>
												<?php endif; if (get_permission('section', 'is_delete')): ?>
													<!--delete link-->
													<?php echo btn_delete('sections/delete/' . $row['id']);?>
												<?php endif; ?>
												</td>
											</tr>
											<?php
												endforeach;
											}else{
												echo '<tr><td colspan="5"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
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