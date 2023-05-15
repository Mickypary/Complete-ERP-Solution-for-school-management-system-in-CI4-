<?php
use App\Libraries\App_lib;

$this->app_lib = new App_lib();

?>


<?php $widget = (is_superadmin_loggedin() ? 4 : 6); ?>
<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><?=translate('select_ground')?></h4>
			</header>
			<?php echo form_open(uri_string(), array('class' => 'validate'));?>
			<div class="panel-body">
				<div class="row mb-sm">
				<?php if (is_superadmin_loggedin() ): ?>
					<div class="col-md-<?php echo $widget; ?>">
						<div class="form-group">
							<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' onchange='getClassByBranch(this.value)'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
						</div>
					</div>
				<?php endif; ?>
					<div class="col-md-<?php echo $widget; ?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
							<?php
								$arrayClass = $this->app_lib->getClass($branch_id);
								echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,1)'
								required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="col-md-<?php echo $widget; ?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
							<?php
								$arraySection = $this->app_lib->getSections(set_value('class_id'), true);
								echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id' required
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-offset-10 col-md-2">
						<button type="submit" name="search" value="1" class="btn btn-default btn-block"> <i class="fas fa-filter"></i> <?=translate('filter')?></button>
					</div>
				</div>
			</footer>
			<?php echo form_close();?>
		</section>

		<?php if ( isset($query) && !empty($query)):?>
		<section class="panel appear-animation" data-appear-animation="<?=$global_config['animations'] ?>" data-appear-animation-delay="100">
			<header class="panel-heading">
				<div class="checkbox-replace pull-right">
					<label class="i-checks"><input type="checkbox" name="chkall" id="selectAllchkbox" checked><i></i> Checked All</label>
				</div>
				<h5 class="panel-title"><i class="fas fa-id-card-alt"></i> <?=translate('student_list')?></h5>
			</header>
			<div class="panel-body">
				<?php
				if($query->getNumRows() != 0){
				$students = $query->getResult();
				?>
				<div class="row checked-area" id="id_card_print">
					<?php foreach($students as $row):?>
					<div class="col-md-4 col-lg-4 col-xl-3 idcard-col">
						<div class="checkbox-replace hidden-print mb-sm">
							<label class="i-checks"><input type="checkbox" name="chk_idcard" id="student_<?=esc($row->student_id)?>" checked><i></i> ID Card Print</label>
						</div>
						<div class="id-card-holder">
							<header class="id-card-heading">
								<center><img class="img-fs" src="<?=base_url('public/uploads/app_image/printing-logo.png')?>" alt="ParyCoder Img" /></center>
							</header>
							<div class="id-card">
								<div class="photo">
									<img src="<?=get_image_url('student', esc($row->photo))?>">
								</div>
								<h5 class="mt-sm text-dark"><?php echo esc($row->fullname); ?></h5>
								<div class="idcard_info">
									<table>
										<tbody>
											<tr>
												<td width="80"><?=translate('father_name')?> </td>
												<td width="8">:</td>
												<td><?=empty($row->parent_id) ? 'N/A' : get_type_name_by_id('parent', $row->parent_id);?></td>
											</tr>
											<tr>
												<td width="80"><?=translate('class')?></td>
												<td width="8">:</td>
												<td><?php echo get_type_name_by_id('class', $row->class_id);?></td>
											</tr>
											<tr>
												<td width="80"><?=translate('roll')?></td>
												<td width="8">:</td>
												<td><?php echo esc($row->roll)?></td>
											</tr>
											<tr>
												<td width="80"><?=translate('section')?></td>
												<td width="8">:</td>
												<td><?php echo get_type_name_by_id('section', $row->section_id);?></td>
											</tr>
											<tr>
												<td width="80"><?=translate('blood_group')?></td>
												<td width="8">:</td>
												<td>
													<?=empty($row->blood_group) ? 'N/A' : $row->blood_group;?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<hr>
								<div class="qr-code">
									<img src="<?php echo base_url('student/create_qrcode/' . "Reg_ID:" . $row->register_no);?>"/>
								</div>
							</div>
						</div>
					</div>
					<?php endforeach;?>
				</div>
				<?php
					}else{
						echo '<div class="alert alert-subl mt-md text-center"><strong>Opps!</strong> ' . translate('no_information_available') . '!</div>'; 
					}
				?>
			</div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-offset-10 col-md-2">
						<button onClick="printElem('id_card_print')" class="btn btn-default btn-block"><i class="fas fa-print"></i> <?=translate('print')?></button>
					</div>
				</div>
			</div>
		</section>
		<?php endif;?>
	</div>
</div>

<script type="text/javascript">
	// print function
	function printElem(elem)
	{
	    var oContent = document.getElementById(elem).innerHTML;
	    var frame1 = document.createElement('iframe');
	    frame1.name = "frame1";
	    frame1.style.position = "absolute";
	    frame1.style.top = "-1000000px";
	    document.body.appendChild(frame1);
	    var frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
	    frameDoc.document.open();
	    //Create a new HTML document.
	    frameDoc.document.write('<html><head><title></title>');
	    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'public/assets/vendor/bootstrap/css/bootstrap.min.css">');
	    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'public/assets/css/custom-style.css">');
	    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'public/assets/css/ramom.css">');
	    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'public/assets/css/idcard.css">');
	    frameDoc.document.write('</head><body>');
	    frameDoc.document.write(oContent);
	    frameDoc.document.write('</body></html>');
	    frameDoc.document.close();
	    setTimeout(function () {
	        window.frames["frame1"].focus();
	        window.frames["frame1"].print();
	        frame1.remove();
	    }, 500);
	    return true;
	}
</script>