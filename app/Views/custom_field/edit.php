
<?php 

use App\Libraries\App_lib;
$this->app_lib = new App_lib();

$this->validation = \Config\Services::validation();


$fieldTypeOptions = array(
	'' => translate('select'), 
    'text' => 'Text Field',
    'textarea' => 'Textarea',
    'dropdown' => 'Select',
    'email' => 'Email',
    'date' => 'Date',
    'checkbox' => 'Checkbox',
    'number' => 'Numeric',
);
 ?>
<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<div class="tabs-custom">
				<ul class="nav nav-tabs">
					<li>
						<a href="<?=base_url('custom_field')?>"><i class="fas fa-list-ul"></i> <?=translate('custom_field') . " " .translate('list')?></a>
					</li>
					<li class="active">
						<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?=translate('edit') . " " . translate('custom_field')?></a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="create">
						<?php echo form_open('custom_field/save', array('class' => 'form-bordered form-horizontal frm-submit')); ?>
						<input type="hidden" name="custom_field_id" value="<?=$customfield['id']?>">
							<?php if (is_superadmin_loggedin() ): ?>
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate('branch')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<?php
										$arrayBranch = $this->app_lib->getSelectList('branch');
										echo form_dropdown("branch_id", $arrayBranch, $customfield['branch_id'], "class='form-control' id='branch_id'
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
									?>
									<span class="error"></span>
								</div>
							</div>
							<?php endif; ?>
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate('custom_field_for')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<?php
										$arrayBelongs = array(
											'' => translate('select'), 
											'online_admission' => translate('online_admission'), 
											'employee' => translate('employee'), 
											'student' => translate('student'), 
											'parents' => translate('parents'), 
										);
										echo form_dropdown("belongs_to", $arrayBelongs, $customfield['form_to'], "class='form-control'
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
									?>
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?=translate('field_label')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="field_label" value="<?=$customfield['field_label']?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate('field_type')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<?php
										echo form_dropdown("field_type", $fieldTypeOptions, $customfield['field_type'], "class='form-control' data-plugin-selectTwo id='field_type'
										data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">Grid (Bootstrap Column) <span class="required">*</span></label>
								<div class="col-md-6">
									<?php
										$options =array('' => translate('select'));
										for ($i=1; $i < 13; $i++) { 
											$options[$i] = 'col-md-' . $i;
										}
										echo form_dropdown("bs_column", $options, $customfield['bs_column'], "class='form-control' data-plugin-selectTwo
										data-width='100%' ");
									?>
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?=translate('order')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="field_order" value="<?=$customfield['field_order']?>" />
									<span class="error"></span>
								</div>
							</div>
                            <div class="form-group" <?=$customfield['field_type'] == 'checkbox' ? '' : 'style="display: none;"'; ?> id="checkbox_type">
                                <label class="col-lg-3 control-label"><?=translate('default_value') ?> <span class="required">*</span></label>
                                <div class="col-md-6">
                                    <?php
                                    $options = array(
                                        '1' => translate('checked'),
                                        '0' => translate('unchecked'),
                                    );
                                    echo form_dropdown('checkbox_default_value', $options, $customfield['default_value'], "class='form-control' data-plugin-selectTwo id='field_type'
									data-width='100%' data-minimum-results-for-search='Infinity'"); ?>
                                </div>
                            </div>
							<div class="form-group" id="common_type" <?php if ($customfield['field_type'] == 'dropdown' || $customfield['field_type'] == 'checkbox') { echo 'style="display: none;"';} ?>>
								<label  class="col-md-3 control-label"><?=translate('default_value')?></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="com_default_value" value="<?=$customfield['default_value']?>" />
								</div>
							</div>
							<div class="form-group" id="dropdown_type" <?=$customfield['field_type'] == 'dropdown' ? '' : 'style="display: none;"'; ?>>
								<label  class="col-md-3 control-label"><?=translate('default_value')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<textarea type="text" rows="2" class="form-control" name="dropdown_default_value" placeholder="Option Separate By Comma"><?=$customfield['default_value']?></textarea>
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-offset-3">
									<div class="ml-md checkbox-replace">
										<label class="i-checks"><input type="checkbox" <?=$customfield['required'] == 1 ? 'checked' : '' ?> name="chk_required"><i></i> This Field is Required ?</label>
									</div>
									<div class="mt-md ml-md checkbox-replace">
										<label class="i-checks"><input type="checkbox" <?=$customfield['show_on_table'] == 1 ? 'checked' : '' ?> name="chk_show_table"><i></i> Show on table</label>
									</div>
									<div class="mt-md ml-md checkbox-replace">
										<label class="i-checks"><input type="checkbox" <?=$customfield['status'] == 1 ? 'checked' : '' ?> name="chk_active" checked=""><i></i> Active</label>
									</div>
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-3">
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
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$('#field_type').on('change', function() {
			var field_type = $(this).val();
			if (field_type == "dropdown") {
				$('#checkbox_type').hide("slow");
				$('#common_type').hide("slow");
				$('#dropdown_type').show("slow");
			} else if (field_type == "checkbox") {
				$('#dropdown_type').hide("slow");
				$('#common_type').hide("slow");
				$('#checkbox_type').show("slow");
			} else {
				$('#checkbox_type').hide("slow");
				$('#dropdown_type').hide("slow");
				$('#common_type').show("slow");
			}
		});
	});
</script>