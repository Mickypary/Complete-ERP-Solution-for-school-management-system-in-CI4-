<?php if (empty($student_id)): ?>
	<div class="row">
		<?php
		$this->db->select('s.id,s.first_name,s.last_name,s.photo,s.register_no,s.birthday,e.class_id,e.section_id,e.roll,e.session_id,c.name as class_name,se.name as section_name');
		$this->db->from('enroll as e');
		$this->db->join('student as s', 'e.student_id = s.id', 'left');
		$this->db->join('class as c', 'e.class_id = c.id', 'left');
		$this->db->join('section as se', 'e.section_id = se.id', 'left');
		$this->db->where('s.parent_id', get_loggedin_user_id());
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$students = $query->result();
			foreach ($students as $row):
		?>
		<div class="col-md-12 mb-lg">
			<div class="profile-head">
				<div class="col-md-12 col-lg-4 col-xl-3">
					<div class="image-content-center user-pro">
						<div class="preview">
							<img src="<?php echo get_image_url('student', $row->photo);?>">
						</div>
					</div>
				</div>
				<div class="col-md-12 col-lg-5 col-xl-5">
					<h5><?=html_escape($row->first_name . " " . $row->last_name)?></h5>
					<p><?=translate('my_child')?></p>
					<ul>
						<li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?=translate('class')?>"><i class="fas fa-school"></i></div><?=html_escape($row->class_name).' ('.html_escape($row->section_name).')'?></li>
						<li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?=translate('roll')?>"><i class="fas fa-award"></i></div><?=html_escape($row->roll)?></li>
						<li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?=translate('register_no')?>"><i class="far fa-registered"></i></div><?=html_escape($row->register_no)?></li>
						<li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?=translate('birthday')?>"><i class="fas fa-birthday-cake"></i></div><?=_d($row->birthday)?></li>
					</ul>
				</div>
				<div class="col-md-12 col-lg-3 col-xl-4">
					<a href="<?=base_url('parents/select_child/' . $row->id);?>" class="chil-shaw btn btn-primary btn-circle pull-right"><i class="fas fa-tachometer-alt"></i> <?=translate('dashboard')?></a>
				</div>
			</div>
		</div>
		<?php endforeach; } else {?>
			<div class="alert alert-subl text-center">
				<strong><i class="fas fa-exclamation-triangle"></i> <?=translate('no_child_was_found')?></strong>
			</div>
		<?php } ?>
	</div>
<?php
else :
    $book_issued = $this->dashboard_model->getMonthlyBookIssued($student_id);
    $get_monthly_payment = $this->dashboard_model->getMonthlyPayment($student_id);
    $fees_summary = $this->dashboard_model->annualFeessummaryCharts($school_id, $student_id);
    $get_student_attendance = $this->dashboard_model->getStudentAttendance($student_id);
    $get_monthly_attachments = $this->dashboard_model->get_monthly_attachments($student_id);
?>

<div class="dashboard-page">
	<div class="row">
		<!-- annual fees summary of students graph -->
		<div class="col-md-12">
			<section class="panel">
				<div class="panel-body">
					<h4 class="chart-title mb-md"><?=translate('my_annual_fee_summary')?></h4>
					<div class="pg-fw">
						<canvas id="fees_graph" style="height:340px;"></canvas>
					</div>
				</div>
			</section>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 col-lg-12 col-sm-12">
			<div class="panel">
			<div class="panel-body">
				<div class="row widget-row-in">
					<div class="col-lg-3 col-sm-6 widget-row-d-br">
						<div class="widget-col-in row">
							<div class="col-md-6 col-sm-6 col-xs-6"> <i class="fas fa-book-reader"></i>
								<h5 class="text-muted"><?=translate('book_issued')?></h5>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<h3 class="counter text-right mt-md text-primary">
									<?=$book_issued?>
								</h3>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="box-top-line line-color-primary">
									<span class="text-muted text-uppercase"><?=translate('interval_month')?></span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-sm-6 widget-row-d-br b-r-none">
						<div class="widget-col-in row">
							<div class="col-md-6 col-sm-6 col-xs-6"> <i class="fas fa-cloud-download-alt"></i>
								<h5 class="text-muted"><?=translate('attachments')?></h5> </div>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<h3 class="counter text-right text-primary">
									<?=$get_monthly_attachments?>
								</h3>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="box-top-line line-color-primary">
										<span class="text-muted text-uppercase"><?=translate('interval_month')?></span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-sm-6 widget-row-d-br">
						<div class="widget-col-in row">
							<div class="col-md-6 col-sm-6 col-xs-6"> <i class="far fa-money-bill-alt" ></i>
								<h5 class="text-muted"><?=translate('fees_payment')?></h5></div>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<h3 class="counter text-right mt-md text-primary">
									<?=$get_monthly_payment?>
								</h3>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="box-top-line line-color-primary">
									<span class="text-muted text-uppercase"><?=translate('interval_month');?></span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-sm-6">
						<div class="widget-col-in row">
							<div class="col-md-6 col-sm-6 col-xs-6"> <i class="fas fa-bullhorn"></i>
								<h5 class="text-muted"><?=translate('events')?></h5>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<h3 class="counter text-right mt-md text-primary">
									<?php
										$this->db->from('event');
										$this->db->where('start_date BETWEEN DATE_SUB(CURDATE() ,INTERVAL 1 MONTH) AND CURDATE() AND branch_id = "'. get_loggedin_branch_id() .'"');
								    	echo $this->db->get()->num_rows();				
									?>
								</h3>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="box-top-line line-color-primary">
										<span class="text-muted text-uppercase"><?=translate('interval_month') ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>

	<!-- annual attendance overview of students -->
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<div class="panel-body">
					<h4 class="chart-title mb-md"><?=translate('my_annual_attendance_overview')?></h4>
					<div class="pg-fw">
						<canvas id="attendance_overview" style="height:380px;"></canvas>
					</div>
				</div>
			</section>
		</div>
	</div>

	<div class="row">
	    <!-- event calendar -->
		<div class="col-md-12">
			<section class="panel">
				<div class="panel-body">
					<div id="event_calendar"></div>
				</div>
			</section>
		</div>
	</div>
</div>

<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-info-circle"></i> <?=translate('event_details')?></h4>
			<div class="panel-btn">
				<button id="print" class="btn btn-default btn-circle icon"><i class="fas fa-print"></i></button>
			</div>
		</header>
		<div class="panel-body">
			<div id="printResult pt-sm pb-sm">
				<div class="table-responsive">						
					<table class="table table-bordered table-condensed text-dark mb-sm tbr-top" id="ev_table">
						
					</table>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-default modal-dismiss">
						<?=translate('close')?>
					</button>
				</div>
			</div>
		</footer>
	</section>
</div>

<script type="application/javascript">
	(function($) {
		"use strict";
		
		// event calendar
		$('#event_calendar').fullCalendar({
			header: {
			left: 'prev,next,today',
			center: 'title',
				right: 'month,agendaWeek,agendaDay,listWeek'
			},
			firstDay: 1,
			height: 720,
			droppable: false,
			editable: true,
	        events: {
	            url: "<?=base_url('event/get_events_list');?>"
	        },
			buttonText: {
				today:    'Today',
				month:    'Month',
				week:     'Week',
				day:      'Day',
				list:     'List'
			},
			eventRender: function(event, element) {
				$(element).on("click", function() {
	                view_event(event.id);
	            });
				if(event.icon){          
					element.find(".fc-title").prepend("<i class='fas fa-"+event.icon+"'></i> ");
				}
			}
		});

		// Own Annual Fee Summary JS
		var total_fees = <?php echo json_encode($fees_summary['total_fee']);?>;
		var total_paid = <?php echo json_encode($fees_summary['total_paid']);?>;
		var total_due = <?php echo json_encode($fees_summary['total_due']);?>;
		var ctx = document.getElementById('fees_graph').getContext('2d');
		var fees_graph = new Chart(ctx, {
			type: 'line',
			data: {
				labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
				datasets: [{
					label: '<?php echo translate("total");?>',
					data: total_fees,
					backgroundColor: 'rgba(216, 27, 96, .6)',
					borderColor: '#F5F5F5',
					borderWidth: 1
				},{
					label: '<?php echo translate("collected");?>',
					data: total_paid,
					backgroundColor: 'rgba(0, 136, 204, .6)',
					borderColor: '#F5F5F5',
					borderWidth: 1
				},{
					label: '<?php echo translate("remaining");?>',
					data: total_due,
					backgroundColor: 'rgba(204, 102, 102, .6)',
					borderColor: '#F5F5F5',
					borderWidth: 1
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				circumference: Math.PI,
				tooltips: {
					mode: 'index',
					bodySpacing: 4
				},
				legend: {
					position: 'bottom',
					labels: {
					boxWidth: 12
				}
				},
				scales: {
					xAxes: [{
						scaleLabel: {
						display: false
						}
					}],
					yAxes: [{
						stacked: true,
						scaleLabel: {
							display: false,
						}
					}]
				}
			}
		});

		//annual attendance overview of students
		var total_present = <?php echo json_encode($get_student_attendance['total_present']);?>;
		var total_absent = <?php echo json_encode($get_student_attendance['total_absent']);?>;
		var total_late = <?php echo json_encode($get_student_attendance['total_late']);?>;

		var ctx = document.getElementById('attendance_overview').getContext('2d');
		var attendance_overview = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
				datasets: [{
					label: '<?php echo translate("total_present");?>',
					data: total_present,
					backgroundColor: 'rgba(71, 164, 71, .6)',
					borderColor: '#F5F5F5',
					borderWidth: 1,
					fill: false,
				},{
					label: '<?php echo translate("total_absent");?>',
					data: total_absent,
					backgroundColor: 'rgba(210, 50, 45, .6)',
					borderColor: '#F5F5F5',
					borderWidth: 1,
					fill: false,
				},{
					label: '<?php echo translate("total_late");?>',
					data: total_late,
					backgroundColor: 'rgba(91, 192, 222, .6)',
					borderColor: '#F5F5F5',
					borderWidth: 1,
					fill: false,
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				circumference: Math.PI,
				tooltips: {
					mode: 'index',
					bodySpacing: 4
				},
				legend: {
					position: 'bottom',
					labels: {
					boxWidth: 12
				}
				},
				scales: {
					xAxes: [{
						scaleLabel: {
						display: false
						}
					}],
					yAxes: [{
						scaleLabel: {
							display: false,
						}
					}]
				}
			}
		});
		
		function view_event(id) {
			$.ajax({
				url: "<?=base_url('event/getDetails')?>",
				type: 'POST',
				data: {
					event_id: id
				},
				success: function (data) {
					$('#ev_table').html(data);
					mfp_modal('#modal');
				}
			});
		}
	})(jQuery);
</script>
<?php endif;?>