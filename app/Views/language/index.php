<?php
use App\Models\ApplicationModel;

$this->db = \Config\Database::connect();
$this->application_model = new ApplicationModel();


?>




<div class="row">
	<div class="col-md-12">
		<?php if (empty($query_language)):?>
			<section class="panel">
				<header class="panel-heading">
					<h4 class="panel-title">
						<i class="fas fa-globe"></i> <?=translate('language_list');?>
						<?php if(get_permission('translations', 'is_add')){ ?>
						<div class="panel-btn">
							<a href="javascript:void(0);" class="add_lang btn btn-default btn-circle">
								<i class="far fa-plus-square"></i> <?=translate('add_language');?>
							</a>
						</div>
						<?php } ?>
					</h4>
				</header>
				<div class="panel-body">
	                <div class="table-responsive mt-md mb-md">
						<table class="table table-bordered table-hover table-condensed">
							<thead>
								<tr>
									<th>#</th>
									<th><?=translate('language')?></th>
									<th><?=translate('flag')?></th>
									<th width="85"><?=translate('stats')?></th>
									<th><?=translate('created_at')?></th>
									<th><?=translate('updated_at')?></th>
									<th><?=translate('action')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$count = 1;
								$languages = $this->db->table('language_list')->get()->getResult();
								foreach($languages as $row) {
									?>
								<tr>
									<td><?php echo $count++;?></td>
									<td><?php echo ucwords($row->name);?></td>
									<td><img class="img-fs" src="<?=$this->application_model->getLangImage($row->id, true)?>" /></td>
									<td>
										<input data-size="mini" data-lang="<?=$row->id?>" class="toggle-switch" data-width="70" data-on="<i class='fas fa-check'></i> ON" data-off="<i class='fas fa-times'></i> OFF" <?=($row->status == 1 ? 'checked' : '');?> data-style="bswitch" type="checkbox">
									</td>
									<td><?php echo _d($row->created_at);?></td>
									<td><?php echo _d($row->updated_at);?></td>
									<td>
										<?php if(get_permission('translations', 'is_view')){ ?>
										<!-- word update link -->
										<a href="<?php echo base_url('translations/update?lang=' . $row->lang_field);?>" class="btn btn-default btn-circle">
											<i class="glyphicon glyphicon-link"></i> <?=translate('edit_word');?> 
										</a>

										<!-- language rename link -->
										<a class="btn btn-default btn-circle edit_modal" href="javascript:void(0);" data-id="<?=$row->id?>">
											<i class="fas fa-pen-nib"></i> <?=translate('rename');?>
										</a>
										<?php } if(get_permission('translations', 'is_delete')){  ?>
										<!-- delete link -->
										<?php echo btn_delete('translations/submitted_data/delete/' . $row->id);?>
										<?php } ?>
									</td>
								</tr>
								<?php  }?>
							</tbody>
						</table>
					</div>
				</div>
			</section>
		<?php 
		else:
		$get_name = $this->db->table('language_list')->select('name')->where('lang_field',$select_language)->get()->getRow()->name;
		?>
			<!-- word update -->
			<section class="panel appear-animation" data-appear-animation="<?=$global_config['animations'] ?>" data-appear-animation-delay="100">
				<header class="panel-heading">
					<h4 class="panel-title"><i class="fas fa-pen-nib"></i> <?=ucfirst($get_name) . ' - ' . translate('translation_update');?></h4>
				</header>
				<?php echo form_open('translations/update?lang=' . $select_language, array('class' => 'validate')); ?>
				<div class="panel-body">
					<table class="table table-bordered table-condensed mb-none table-export">
						<thead>
							<tr>
								<th>ID</th>
								<th><?=translate('word')?></th>
								<th><?=translate('translations')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							$words = $query_language->getResult();
								foreach($words as $row) {
							?>
							<tr>
								<td><?php echo $count++;?></td>
								<td><?php echo ucwords(str_replace('_', ' ',  $row->word));?></td>
								<td>
									<div style="width:  100%">
									<div class="input-group">
										<span class="input-group-addon">
											<span class="icon"><i class="far fa-comment-alt"></i></span>
										</span>
										<input  type="text" placeholder="Set Word Translation" name="word_<?=$row->word?>" value="<?=$row->$select_language?>" class="form-control" />
									</div>
									</div>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<footer class="panel-footer">
				<div class="row">
					<div class="col-md-offset-10 col-md-2">
						<button class="btn btn btn-default btn-block" name="submit" value="update"><i class="fas fa-edit"></i> <?=translate('update')?></button>
					</div>
				</div>
				</footer>
				<?php echo form_close();?>
			</section>
		<?php endif;?>
		
	<?php if(get_permission('translations', 'is_add')){ ?>
		<!-- language add modal -->
		<div id="add_modal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
			<section class="panel">
				<?php
					echo form_open_multipart(base_url('translations/submitted_data/create'), array(
						'class' 	=> 'validate',
						'method' 	=> 'post'
					));
				?>
				<div class="panel-heading">
					<h4 class="panel-title">
						<i class="far fa-plus-square"></i> <?=translate('add_language')?>
					</h4>
				</div>

				<div class="panel-body">
					<div class="form-group mb-md">
						<label class="control-label"><?=translate('language')?> <span class="required">*</span></label>
						<input type="text" class="form-control" name="name" required  value="">
					</div>
					<div class="form-group mb-md">
						<label class="control-label"><?=translate('flag_icon')?></label>
						<input type="file" name="flag" data-height="90" class="dropify" data-allowed-file-extensions="jpg png bmp" />
					</div>
				</div>
				<footer class="panel-footer">
					<div class="text-right">
						<button type="submit" class="btn btn-default"><?=translate('save')?></button>
						<button class="btn btn-default modal-dismiss"><?=translate('cancel')?></button>
					</div>
				</footer>
				<?php echo form_close();?>
			</section>
		</div>
	<?php } ?>
		
		<!-- language edit modal -->
		<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
			<section class="panel">
				<?php
					echo form_open_multipart(base_url(''), array(
						'id' => 'modalfrom',
						'class' => 'validate',
						'method' => 'post'
					));
				?>
					<header class="panel-heading">
						<h4 class="panel-title"><i class="far fa-edit"></i> <?=translate('rename')?></h4>
					</header>
					<div class="panel-body">
						<div class="form-group mb-md">
							<label class="control-label"><?=translate('name')?> <span class="required">*</span></label>
							<input type="text" class="form-control" name="rename" id="modal_name" required  value="" />
						</div>
						<div class="form-group mb-md">
							<label class="control-label"><?=translate('flag_icon')?></label>
							<input type="file" name="flag" data-height="80" class="dropify" data-allowed-file-extensions="jpg png bmp" />
						</div>

					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-default"><?=translate('update')?></button>
								<button class="btn btn-default modal-dismiss"><?=translate('cancel')?></button>
							</div>
						</div>
					</footer>
				<?php echo form_close();?>
			</section>
		</div>
		
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		// for edit modal 
		$(document).on('click', '.edit_modal', function () {
			var id = $(this).data('id');
			$.ajax({
				url: "<?=base_url('translations/get_details')?>",
				type: 'POST',
				data: {id: id},
				dataType: 'json',
				success: function (res) {
					$('#modal_name').val(res.name);
					$('#modalfrom').attr('action', '<?php echo base_url("translations/submitted_data/rename/");?>' + res.id); 
					mfp_modal('#modal');
				}
			});
		});
		
		$(document).on('click', '.add_lang', function () {
			mfp_modal('#add_modal');
		});
		
		// for check and uncheck language status
		$(document).on('change', '.toggle-switch', function() {
			var state = $(this).prop('checked');
			var lang_id = $(this).data('lang');
			
			$.ajax({
				type: 'POST',
				url: "<?=base_url('translations/status')?>",
				data: {
					lang_id: lang_id,
					status: state
				},
				dataType: "html",
				success: function(data) {
					swal({
						type: 'success',
						title: "<?=translate('successfully')?>",
						text: data,
						showCloseButton: true,
						focusConfirm: false,
						buttonsStyling: false,
						confirmButtonClass: 'btn btn-default swal2-btn-default',
						footer: '*Note : You can undo this action at any time'
					});
				}
			});
		});
	});
</script>