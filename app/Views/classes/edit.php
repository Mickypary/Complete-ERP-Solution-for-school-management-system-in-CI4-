<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();
$this->db = \Config\Database::connect();
?>


<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?=base_url('classes')?>"><i class="fas fa-graduation-cap"></i> <?=translate('class')?></a>
			</li>
<?php if (get_permission('section', 'is_view')): ?>
			<li>
				<a href="<?=base_url('sections')?>"><i class="fas fa-award"></i> <?=translate('section')?></a>
			</li>
<?php endif; ?>
			<li class="active">
				<a href="#edit" data-toggle="tab"><i class="fas fa-pen-nib"></i> <?=translate('edit_class')?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="edit">
				<?php echo form_open(uri_string(), array('class' => 'form-horizontal form-bordered frm-submit'));?>
					<input type="hidden" name="class_id" value="<?=$class['id']?>">
					<?php if (is_superadmin_loggedin()): ?>
						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('branch')?> <span class="required">*</span></label>
							<div class="col-md-6">
								<?php
									$arrayBranch = $this->app_lib->getSelectList('branch');
									echo form_dropdown("branch_id", $arrayBranch, $class['branch_id'], "class='form-control' id='branch_id' data-width='100%'
									onchange='getSectionByBranch(this.value)' data-plugin-selectTwo  data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"></span>
							</div>
						</div>
					<?php endif; ?>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label"><?=translate('name')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="name" value="<?=$class['name']?>"/>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('class_numeric')?></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="name_numeric" value="<?=$class['name_numeric']?>"/>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('section')?> <span class="required">*</span></label>
						<div class="col-md-6 mb-md">
							<?php
								$query = $this->db->table('sections_allocation')->getWhere(array('class_id' => $class['id']))->getResultArray();
								$sel = array_column($query,'section_id');
								$arraySection = array();
								$result = $this->db->table('section')->where('branch_id', $class['branch_id'])->get()->getResult();
								foreach ($result as $row) {
									$arraySection[$row->id] = $row->name;
								}
								echo form_dropdown("sections[]", $arraySection, $sel, "class='form-control mb-sm' id='section_id'
								data-plugin-selectTwo data-width='100%' multiple data-plugin-options='{" . '"placeholder" : "' . translate('select_branch_first') . '" ' ."}'");
							?>
							<span class="error"></span>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?=translate('update')?>
								</button>
							</div>
						</div>
					</footer>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</section>