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
			<li>
				<a href="<?=base_url('sections')?>"><i class="fas fa-award"></i> <?=translate('section')?></a>
			</li>
			<li class="active">
				<a href="#edit" data-toggle="tab"><i class="fas fa-pen-nib"></i> <?=translate('edit_section')?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="edit">
				<?php echo form_open('sections/save', array('class' => 'form-horizontal form-bordered frm-submit'));?>
					<input type="hidden" name="section_id" value="<?=$section['id']?>">
				<?php if (is_superadmin_loggedin()): ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, $section['branch_id'], "class='form-control'  data-width='100%'
								onchange='getSectionByBranch(this.value)' data-plugin-selectTwo  data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"></span>
						</div>
					</div>
				<?php endif; ?>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('name')?> <span class="required">*</span></label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="name" value="<?=$section['name']?>"/>
						<span class="error"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('capacity')?></label>
					<div class="col-md-6 mb-md">
						<input type="text" class="form-control" name="capacity" value="<?=$section['capacity']?>" />
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