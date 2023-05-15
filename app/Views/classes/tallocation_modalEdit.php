<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();


?>




<header class="panel-heading">
<h4 class="panel-title"><i class="far fa-edit"></i> <?=translate('edit_assign')?></h4>
</header>
<input type="hidden" name="allocation_id" value="<?=$data['id']?>">
<div class="panel-body">
	<?php if (is_superadmin_loggedin()): ?>
	<div class="form-group">
		<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
		<?php
			$arrayBranch = $this->app_lib->getSelectList('branch');
			echo form_dropdown("branch_id", $arrayBranch, $data['branch_id'], "class='form-control selecttwo' id='branch_id'");
		?>
		<span class="error"></span>
	</div>
	<?php endif; ?>
	<div class="form-group">
		<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
		<?php
			$arrayClass = $this->app_lib->getClass($data['branch_id']);
			echo form_dropdown("class_id", $arrayClass, $data['class_id'], "class='form-control selecttwo' id='class_id' onchange='getSectionByClass(this.value,0)' ");
		?>
		<span class="error"></span>
	</div>
	<div class="form-group">
		<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
		<?php
			$arraySection = $this->app_lib->getSections($data['class_id']);
			echo form_dropdown("section_id", $arraySection, $data['section_id'], "class='form-control selecttwo' id='section_id' ");
		?>
		<span class="error"></span>
	</div>
	<div class="form-group mb-md">
		<label class="control-label"><?=translate('class_teacher')?> <span class="required">*</span></label>
		<?php
			$arrayTeacher = $this->app_lib->getStaffList($data['branch_id'], 3);
			echo form_dropdown("staff_id", $arrayTeacher, $data['teacher_id'], "class='form-control selecttwo' id='staff_id' ");
		?>
		<span class="error"></span>
	</div>
</div>
<footer class="panel-footer">
	<div class="row">
		<div class="col-md-12 text-right">
			<button class="btn btn-default mr-xs" type="submit" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
				<i class="fas fa-plus-circle"></i> <?php echo translate('update'); ?>
			</button>
			<button class="btn btn-default modal-dismiss"><?php echo translate('close'); ?></button>
		</div>
	</div>
</footer>


