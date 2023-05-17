<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();

?>




<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?=base_url('subject/index')?>"><i class="fas fa-list-ul"></i> <?=translate('subject') . ' ' . translate('list')?></a>
			</li>
			<li class="active">
				<a href="#edit" data-toggle="tab"><i class="far fa-edit"></i> <?=translate('edit') . ' ' . translate('subject')?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="edit" class="tab-pane active">
				<?php echo form_open('subject/save', array('class' => 'form-horizontal form-bordered frm-submit'));?>
					<?php if (is_superadmin_loggedin()): ?>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate('branch')?> <span class="required">*</span></label>
							<div class="col-md-6">
								<?php
									$arrayBranch = $this->app_lib->getSelectList('branch');
									echo form_dropdown("branch_id", $arrayBranch, $subject['branch_id'], "class='form-control' data-width='100%'
									data-plugin-selectTwo  data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"></span>
							</div>
						</div>
					<?php endif; ?>
					<div class="form-group">
						<label class="col-md-3 control-label">
							<?=translate('subject_name')?> <span class="required">*</span>
						</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="name" value="<?=$subject['name']?>" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">
							<?=translate('subject_code')?> <span class="required">*</span>
						</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="subject_code" value="<?=$subject['subject_code']?>" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('subject_author')?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="subject_author" value="<?=$subject['subject_author']?>" />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('subject_type')?> <span class="required">*</span></label>
						<div class="col-md-6 mb-md">
						<?php
							$subjectArray = array(
								'Theory' => 'Theory',
								'Practical' => 'Practical',
								'Optional' => 'Optional',
								'Mandatory' => 'Mandatory'
							);
							echo form_dropdown("subject_type", $subjectArray, $subject['subject_type'], "class='form-control populate' data-plugin-selectTwo data-width='100%'
							data-minimum-results-for-search='Infinity' ");
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
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>