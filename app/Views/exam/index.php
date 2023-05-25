<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();
$this->db = \Config\Database::connect();

?>





<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?=translate('exam_list')?></a>
			</li>
<?php if (get_permission('exam', 'is_add')): ?>
			<li>
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?=translate('create_exam')?></a>
			</li>
<?php endif; ?>
		</ul>
		<div class="tab-content">
			<div id="list" class="tab-pane active">
				<table class="table table-bordered table-hover mb-none table-export">
					<thead>
						<tr>
							<th width="50"><?=translate('sl')?></th>
						<?php if (is_superadmin_loggedin()): ?>
							<th><?=translate('branch')?></th>
						<?php endif; ?>
							<th><?=translate('exam_name')?></th>
							<th><?=translate('exam_type')?></th>
							<th><?=translate('term')?></th>
							<th><?=translate('mark_distribution')?></th>
							<th><?=translate('remarks')?></th>
							<th><?=translate('action')?></th>
						</tr>
					</thead>
					<tbody>
						<?php $count = 1; foreach($examlist as $row): ?>
						<tr>
							<td><?php echo $count++; ?></td>
						<?php if (is_superadmin_loggedin()): ?>
							<td><?php echo $row['branch_name']; ?></td>
						<?php endif; ?>
							<td><?php echo $row['name']; ?></td>
							<td><?php 
							if ($row['type_id'] == 1) {
								echo translate('marks');
							} elseif ($row['type_id'] == 2) {
								echo translate('grade');
							} elseif ($row['type_id'] == 3) {
								echo translate('Mid');
							} elseif($row['type_id'] == 4) {
								echo translate('XmasTerm');
							} elseif($row['type_id'] == 5) {
								echo translate('LentTerm');
							} elseif($row['type_id'] == 6) {
								echo translate('SummerTerm');
							}

							 ?></td>
							<td><?php echo (empty($row['term_id']) ? 'N/A' : get_type_name_by_id('exam_term', $row['term_id'])); ?></td>
							<td><?php
								$distribution = json_decode($row['mark_distribution'], true);
								if (!empty($distribution)) {
									foreach ($distribution as $id) {
										echo '- ' . get_type_name_by_id('exam_mark_distribution', $id) . '<br>';
									}
								}
							 ?></td>
							<td><?php echo $row['remark']; ?></td>
							<td class="min-w-xs">
							<?php if (get_permission('exam', 'is_edit')): ?>
								<!-- updatr link -->
								<a href="<?php echo base_url('exam/edit/' . $row['id']);?>" class="btn btn-default btn-circle icon">
									<i class="fas fa-pen-nib"></i>
								</a>
							<?php endif; if (get_permission('exam', 'is_delete')): ?>
								<!-- delete link -->
								<?php echo btn_delete('exam/delete/' . $row['id']);?>
							<?php endif; ?>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
<?php if (get_permission('exam', 'is_add')): ?>
			<div class="tab-pane" id="create">
				<?php echo form_open(uri_string(), array('class' => 'frm-submit'));?>
					<div class="form-horizontal form-bordered mb-lg">
						<?php if (is_superadmin_loggedin()): ?>
						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('branch')?> <span class="required">*</span></label>
							<div class="col-md-6">
								<?php
									$arrayBranch = $this->app_lib->getSelectList('branch');
									echo form_dropdown("branch_id", $arrayBranch, $branch_id, "class='form-control' id='branch_id'
									id='branch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"></span>
							</div>
						</div>
						<?php endif; ?>
						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('name')?> <span class="required">*</span></label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" />
								<span class="error"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('term')?></label>
							<div class="col-md-6">
								<?php
									$array = $this->app_lib->getSelectByBranch('exam_term', $branch_id);
									echo form_dropdown("term_id", $array, set_value('term_id'), "class='form-control' id='term_id'
									data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
								?>
								<span class="error"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('exam_type')?></label>
							<div class="col-md-6">
								<?php
									$arrayType = array(
										'' => translate('select'), 
										'1' => translate('marks'), 
										'2' => translate('grade'), 
										'3' => translate('Mid'), 
										'4' => translate('XmasTerm'), 
										'5' => translate('LentTerm'), 
										'6' => translate('SummerTerm'), 
									);
									echo form_dropdown("type_id", $arrayType, set_value('type_id'), "class='form-control' id='type_id'
									data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
								?>
								<span class="error"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('mark_distribution')?></label>
							<div class="col-md-6">
								<?php
									$arraySection = array();
									if (!is_superadmin_loggedin()){
										$result = $this->db->table('exam_mark_distribution')->where('branch_id',get_loggedin_branch_id())->get()->getResult();
										foreach ($result as $row) {
											$arraySection[$row->id] = $row->name;
										}
									}
									echo form_dropdown("mark_distribution[]", $arraySection, set_value('mark_distribution[]'), "class='form-control' multiple id='mark_distribution'
									data-plugin-selectTwo data-width='100%'");
								?>
								<span class="error"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('remarks')?></label>
							<div class="col-md-6 mb-sm">
								<textarea rows="2" class="form-control" name="remark"></textarea>
							</div>
						</div>
					</div>

					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?=translate('save')?>
								</button>
							</div>
						</div>
					</footer>
				<?php echo form_close();?>
			</div>
<?php endif; ?>
		</div>
	</div>
</section>

<script type="text/javascript">
	$(document).ready(function () {
		$(document).on('change', '#branch_id', function() {
			var branchID = $(this).val();
			$.ajax({
				url: "<?=base_url('ajax/getDataByBranch')?>",
				type: 'POST',
				data: {
					branch_id: branchID,
					table: 'exam_term'
				},
				success: function (data) {
					$('#term_id').html(data);
				}
			});

			$.ajax({
				url: "<?=base_url('exam/getDistributionByBranch')?>",
				type: 'POST',
				data: {
					branch_id: branchID,
				},
				success: function (data) {
					$('#mark_distribution').html(data);
				}
			});
		});
	});
</script>