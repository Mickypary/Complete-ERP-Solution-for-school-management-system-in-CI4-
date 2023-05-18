<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();

?>



<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?=base_url('exam/grade')?>"><i class="fas fa-list-ul"></i> <?=translate('grade_list')?></a>
			</li>
			<li class="active">
				<a href="#edit" data-toggle="tab" ><i class="far fa-edit"></i> <?=translate('edit_grade')?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="edit">
				<?php echo form_open(uri_string(), array('class' => 'form-horizontal form-bordered frm-submit'));?>
				<input type="hidden" name="grade_id" value="<?=$grade['id']?>" />
				<?php if (is_superadmin_loggedin()): ?>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('branch')?> <span class="required">*</span></label>
					<div class="col-md-6">
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, $grade['branch_id'], "class='form-control'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"></span>
					</div>
				</div>
				<?php endif; ?>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('name')?> <span class="required">*</span></label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="name" value="<?=$grade['name']?>" />
						<span class="error"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('grade_point')?> <span class="required">*</span></label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="grade_point" value="<?=$grade['grade_point']?>" />
						<span class="error"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('min_percentage')?> <span class="required">*</span></label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="lower_mark" value="<?=$grade['lower_mark']?>" />
						<span class="error"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('max_percentage')?> <span class="required">*</span></label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="upper_mark" value="<?=$grade['upper_mark']?>" />
						<span class="error"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('remarks')?></label>
					<div class="col-md-6 mb-md">
						<input type="text" class="form-control" name="remark" value="<?=$grade['remark']?>" />
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