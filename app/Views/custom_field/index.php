<?php 
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
					<li class="active">
						<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?=translate('custom_field') . " " .translate('list')?></a>
					</li>
<?php if (get_permission('custom_field', 'is_add')): ?>
					<li >
						<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?=translate('add') . " " . translate('custom_field')?></a>
					</li>
<?php endif; ?>
				</ul>
				<div class="tab-content">
					<div id="list" class="tab-pane active">
						<div class="mb-md">
							<table class="table table-bordered table-hover table-condensed mb-none table-export">
								<thead>
									<tr>
										<th><?=translate('sl')?></th>
									<?php if (is_superadmin_loggedin()) { ?>
										<th><?=translate('branch')?></th>
									<?php } ?>
										<th><?=translate('custom_field_for')?></th>
										<th><?=translate('label')?></th>
										<th><?=translate('type')?></th>
										<th><?=translate('order')?></th>
										<th><?=translate('status')?></th>
										<th><?=translate('action')?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$count = 1;
									foreach($customfield as $row):
										?>
									<tr>
										<td><?php echo $count++; ?></td>
									<?php if (is_superadmin_loggedin()) { ?>
										<td><?php echo get_type_name_by_id('branch', $row['branch_id']);?></td>
									<?php } ?>
										<td><?php echo translate($row['form_to']);?></td>
										<td><?php echo $row['field_label'];?></td>
										<td><?php echo $fieldTypeOptions[$row['field_type']];?></td>
										<td><?php echo $row['field_order'];?></td>
										<td>
											<div class="material-switch ml-xs">
												<input class="customfield-switch" id="switch_<?=$row['id']?>" data-id="<?=$row['id']?>" name="customfield_switch<?=$row['id']?>" 
												type="checkbox" <?php echo ($row['status'] == 1 ? 'checked' : ''); ?> />
												<label for="switch_<?=$row['id']?>" class="label-primary"></label>
											</div>
										</td>
										<td class="min-w-c">
										<?php if (get_permission('custom_field', 'is_edit')): ?>
											<!--update link-->
											<a href="<?=base_url('custom_field/edit/' . $row['id'])?>" class="btn btn-default btn-circle icon">
												<i class="fas fa-pen-nib"></i>
											</a>
										<?php endif; if (get_permission('custom_field', 'is_delete')): ?>
											<!-- delete link -->
											<?php echo btn_delete('custom_field/delete/' . $row['id']);?>
										<?php endif; ?>
										</td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane" id="create">
						<?php echo form_open('custom_field/save', array('class' => 'form-bordered form-horizontal frm-submit')); ?>
							<?php if (is_superadmin_loggedin() ): ?>
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate('branch')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<?php
										$arrayBranch = $this->app_lib->getSelectList('branch');
										echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id'
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
										echo form_dropdown("belongs_to", $arrayBelongs, set_value('belongs_to'), "class='form-control'
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
									?>
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?=translate('field_label')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="field_label" value="<?=set_value('field_label')?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?=translate('field_type')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<?php
										echo form_dropdown("field_type", $fieldTypeOptions, set_value('field_type'), "class='form-control' data-plugin-selectTwo id='field_type'
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
										echo form_dropdown("bs_column", $options, set_value('bs_column'), "class='form-control' data-plugin-selectTwo
										data-width='100%' ");
									?>
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?=translate('order')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="field_order" value="<?=set_value('field_order')?>" />
									<span class="error"></span>
								</div>
							</div>
                            <div class="form-group" style="display: none;" id="checkbox_type">
                                <label class="col-lg-3 control-label"><?=translate('default_value') ?> <span class="required">*</span></label>
                                <div class="col-md-6">
                                    <?php
                                    $options = array(
                                        '1' => translate('checked'),
                                        '0' => translate('unchecked'),
                                    );
                                    echo form_dropdown('checkbox_default_value', $options, '', "class='form-control' data-plugin-selectTwo id='field_type'
									data-width='100%' data-minimum-results-for-search='Infinity'"); ?>
                                </div>
                            </div>
							<div class="form-group" id="common_type">
								<label  class="col-md-3 control-label"><?=translate('default_value')?></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="com_default_value" value="<?=set_value('com_default_value')?>" />
								</div>
							</div>
							<div class="form-group" id="dropdown_type" style="display: none;">
								<label  class="col-md-3 control-label"><?=translate('default_value')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<textarea type="text" rows="2" class="form-control" name="dropdown_default_value" placeholder="Option Separate By Comma"><?=set_value('dropdown_default_value')?></textarea>
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-offset-3">
									<div class="ml-md checkbox-replace">
										<label class="i-checks"><input type="checkbox" name="chk_required"><i></i> This Field is Required ?</label>
									</div>
									<div class="mt-md ml-md checkbox-replace">
										<label class="i-checks"><input type="checkbox" name="chk_show_table"><i></i> Show on table</label>
									</div>
									<div class="mt-md ml-md checkbox-replace">
										<label class="i-checks"><input type="checkbox" name="chk_active" checked=""><i></i> Active</label>
									</div>
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-3">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?=translate('save')?>
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
		// custom field status
		$(".customfield-switch").on("change", function() {
			var state = $(this).prop('checked');
			var id = $(this).data('id');
			if (state != null) {
				$.ajax({
					type: 'POST',
					url: base_url + "custom_field/status",
					data: {
						id: id,
						status: state
					},
					dataType: "json",
					success: function(data) {
						if(data.status == true) {
							alertMsg(data.msg);
						} else {
							window.location.href = base_url + "dashboard";
						}
					}
				});
			}
		});

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