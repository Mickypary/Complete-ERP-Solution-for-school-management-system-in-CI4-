<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();
$this->validation = \Config\Services::validation();


?>



<div class="row">
<?php if (get_permission('exam_hall', 'is_add')): ?>
	<div class="col-md-5">
		<section class="panel">
			<?php echo form_open(uri_string()); ?>
				<header class="panel-heading">
					<h4 class="panel-title"><i class="far fa-edit"></i> <?=translate('add') . " " . translate('exam_hall')?></h4>
				</header>
				<div class="panel-body">
					<?php if (is_superadmin_loggedin()): ?>
					<div class="form-group">
						<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control'
						   	data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"><?=$this->validation->getError('branch_id')?></span>
					</div>
					<?php endif; ?>
					<div class="form-group">
						<label class="control-label"><?=translate('hall_no')?> <span class="required">*</span></label>
						<input type="text" class="form-control" name="hall_no" value="<?=set_value('hall_no')?>" />
						<span class="error"><?=$this->validation->getError('hall_no')?></span>
					</div>
					<div class="form-group mb-md">
						<label class="control-label"> <?=translate('no_of_seats')?> <span class="required">*</span></label>
						<input type="text" class="form-control" name="no_of_seats" value="<?=set_value('no_of_seats')?>" />
						<span class="error"><?=$this->validation->getError('no_of_seats')?></span>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-12">
							<button class="btn btn-default pull-right" type="submit" name="save" value="1">
								<i class="fas fa-plus-circle"></i> <?=translate('save')?>
							</button>
						</div>	
					</div>
				</div>
			<?php echo form_close();?>
		</section>
	</div>
<?php endif; ?>
<?php if (get_permission('exam_hall', 'is_view')): ?>
	<div class="col-md-<?php if (get_permission('exam_hall', 'is_add')){ echo "7"; }else{ echo "12"; } ?>">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-list-ul"></i> <?=translate('exam_hall') . " " . translate('list')?></h4>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none">
						<thead>
							<tr>
								<th><?=translate('sl')?></th>
								<th><?=translate('branch')?></th>
								<th><?=translate('hall_no')?></th>
								<th><?=translate('no_of_seats')?></th>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
						<?php
						$count = 1;
						if (count($halllist)){
							foreach ($halllist as $row):
								?>
							<tr>
								<td><?php echo $count++;?></td>
								<td><?php echo $row['branch_name'];?></td>
								<td><?php echo $row['hall_no']; ?></td>
								<td><?php echo $row['seats'];?></td>
								<td>
								<?php if (get_permission('exam_hall', 'is_edit')): ?>
									<!-- update link  -->
									<a class="btn btn-default btn-circle icon" href="javascript:void(0);" onclick="getHallModal(this)"
									data-id="<?=$row['id']?>" data-number="<?=$row['hall_no']?>" data-seats="<?=$row['seats']?>" data-branch="<?=$row['branch_id']?>">
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; if (get_permission('exam_hall', 'is_delete')): ?>
									<!-- delete link -->
									<?php echo btn_delete('exam/hall_delete/' . $row['id']);?>
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
<?php endif; ?>
<?php if (get_permission('exam_hall', 'is_edit')): ?>
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<section class="panel">
		<?php echo form_open('exam/hall_edit', array('class' => 'frm-submit')); ?>
			<header class="panel-heading">
				<h4 class="panel-title">
					<i class="far fa-edit"></i> <?=translate('edit') . " " . translate('exam_hall')?>
				</h4>
			</header>
			<div class="panel-body">
				<input type="hidden" name="hall_id" id="hall_id" value="">
				<?php if (is_superadmin_loggedin()): ?>
				<div class="form-group">
					<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
					<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control'
						id='ebranch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
					<span class="error"></span>
				</div>
				<?php endif; ?>
				<div class="form-group">
					<label class="control-label"><?=translate('hall_no')?> <span class="required">*</span></label>
					<input type="text" class="form-control" name="hall_no" id="ehall_no" value="" />
					<span class="error"></span>
				</div>
				<div class="form-group mb-md">
					<label class="control-label"><?=translate('no_of_seats')?> <span class="required">*</span></label>
					<input type="number" class="form-control" name="no_of_seats" id="eno_of_seats" value="" />
					<span class="error"></span>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
							<i class="fas fa-plus-circle"></i> <?=translate('update')?>
						</button>
						<button class="btn btn-default modal-dismiss"><?=translate('cancel')?></button>
					</div>
				</div>
			</footer>
		<?php echo form_close();?>
	</section>
</div>
<?php endif; ?>
