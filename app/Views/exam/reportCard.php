<style type="text/css">
	@media print {
		.pagebreak {
			page-break-before: always;
		}
	}
	.mark-container {
	    background: #fff;
	    width: 1000px;
	    position: relative;
	    z-index: 2;
	    margin: 0 auto;
	    padding: 20px 30px;
	}
	table {
	    border-collapse: collapse;
	    width: 100%;
	    margin: 0 auto;
	}
</style>

<?php
use App\Models\ExamModel;

$this->db = \Config\Database::connect();
$this->exam_model = new ExamModel();




if (count($student_array)) {
	foreach ($student_array as $sc => $studentID) {
		$result = $this->exam_model->getStudentReportCard($studentID, $examID, $sessionID);
		$student = $result['student'];
		$getMarksList = $result['exam'];

		$getExam = $this->db->table('exam')->where(array('id' => $examID))->get()->getRowArray();
		$getSchool = $this->db->table('branch')->where(array('id' => $getExam['branch_id']))->get()->getRowArray();
		$schoolYear = get_type_name_by_id('schoolyear', $sessionID, 'school_year');
		?>
	<div class="mark-container">
		<table border="0" style="margin-top: 20px; height: 100px;">
			<tbody>
				<tr>
				<td style="width:40%;vertical-align: top;"><img style="max-width:225px;" src="<?php echo base_url('public/uploads/app_image/report-card-logo.png');?>"></td>
				<td style="width:60%;vertical-align: top;">
					<table align="right" class="table-head text-right" >
						<tbody>
							<tr><th style="font-size: 26px;" class="text-right"><?=$getSchool['school_name']?></th></tr>
							<tr><th style="font-size: 14px; padding-top: 4px;" class="text-right">Academic Session : <?=$schoolYear?></th></tr>
							<tr><td><?=$getSchool['address']?></td></tr>
							<tr><td><?=$getSchool['mobileno']?></td></tr>
							<tr><td><?=$getSchool['email']?></td></tr>
						</tbody>
					</table>
				</td>
				</tr>
			</tbody>
		</table>
		<table class="table table-bordered" style="margin-top: 20px;">
			<tbody>
				<tr>
					<th>Name</td>
					<td><?=$student['first_name'] . " " . $student['last_name']?></td>
					<th>Register No</td>
					<td><?=$student['register_no']?></td>
					<th>Roll Number</td>
					<td><?=$student['roll']?></td>
				</tr>
				<tr>
					<th>Father Name</td>
					<td><?=$student['father_name']?></td>
					<th>Admission Date</td>
					<td><?=_d($student['admission_date'])?></td>
					<th>Date of Birth</td>
					<td><?=_d($student['birthday'])?></td>
				</tr>
				<tr>
					<th>Mother Name</td>
					<td><?=$student['mother_name']?></td>
					<th>Class</td>
					<td><?=$student['class_name'] . " (" . $student['section_name'] . ")"?></td>
					<th>Gender</td>
					<td><?=ucfirst($student['gender'])?></td>
				</tr>
			</tbody>
		</table>
		<table class="table table-condensed table-bordered mt-lg">
			<thead>
				<tr>
					<th>Subjects</th>
				<?php 
				$markDistribution = json_decode($getExam['mark_distribution'], true);
				foreach ($markDistribution as $id) {
					?>
					<th><?php echo get_type_name_by_id('exam_mark_distribution',$id)  ?></th>
				<?php } ?>
				<?php if ($getExam['type_id'] == 1) { ?>
					<th>Total</th>
				<?php } elseif($getExam['type_id'] == 2) { ?>
					<th>Grade</th>
					<th>Point</th>
				<?php } elseif ($getExam['type_id'] == 3) { ?>
					<th>Total</th>
					<th>Grade</th>
					<th>Point</th>
				<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php
			$colspan = count($markDistribution) + 1;
			$total_grade_point = 0;
			$grand_obtain_marks = 0;
			$grand_full_marks = 0;
			$result_status = 1;
			foreach ($getMarksList as $row) {
			?>
				<tr>
					<td valign="middle" width="35%"><?=$row['subject_name']?></td>
				<?php 
				$total_obtain_marks = 0;
				$total_full_marks = 0;
				$fullMarkDistribution = json_decode($row['mark_distribution'], true);
				$obtainedMark = json_decode($row['get_mark'], true);
				foreach ($fullMarkDistribution as $i => $val) {
					$obtained_mark = floatval($obtainedMark[$i]);
					$fullMark = floatval($val['full_mark']);
					$passMark = floatval($val['pass_mark']);
					if ($obtained_mark < $passMark) {
						$result_status = 0;
					}

					$total_obtain_marks += $obtained_mark;
					$obtained = $row['get_abs'] == 'on' ? 'Absent' : $obtained_mark;
					$total_full_marks += $fullMark;
					?>
				<?php if ($getExam['type_id'] == 1 || $getExam['type_id'] == 3){ ?>
					<td valign="middle">
						<?php 
							if ($row['get_abs'] == 'on') {
								echo 'Absent';
							} else {
								echo $obtained_mark . '/' . $fullMark;
							}
						?>
					</td>
				<?php } if ($getExam['type_id'] == 2){ ?>
					<td valign="middle">
						<?php 
							if ($row['get_abs'] == 'on') {
								echo 'Absent';
							} else {
								$percentage_grade = ($obtained_mark * 100) / $fullMark;
								$grade = $this->exam_model->get_grade($percentage_grade, $getExam['branch_id']);
								echo $grade['name'];
							}
						?>
					</td>
				<?php } ?>
				<?php
				}
				$grand_obtain_marks += $total_obtain_marks;
				$grand_full_marks += $total_full_marks;
				?>
				<?php if($getExam['type_id'] == 1 || $getExam['type_id'] == 3) { ?>
					<td valign="middle"><?=$total_obtain_marks . "/" . $total_full_marks?></td>
				<?php } if($getExam['type_id'] == 2) { 
					$percentage_grade = ($total_obtain_marks * 100) / $total_full_marks;
					$grade = $this->exam_model->get_grade($percentage_grade, $getExam['branch_id']);
					$total_grade_point += $grade['grade_point'];
					?>
					<td valign="middle"><?=$grade['name']?></td>
					<td valign="middle"><?=number_format($grade['grade_point'], 2, '.', '')?></td>
				<?php } if ($getExam['type_id'] == 3) {
					$colspan += 2;
					$percentage_grade = ($total_obtain_marks * 100) / $total_full_marks;
					$grade = $this->exam_model->get_grade($percentage_grade, $getExam['branch_id']);
					$total_grade_point += $grade['grade_point'];
					?>
					<td valign="middle"><?=$grade['name']?></td>
					<td valign="middle"><?=number_format($grade['grade_point'], 2, '.', '')?></td>
				<?php } ?>
				</tr>
			<?php } ?>
			<?php if ($getExam['type_id'] == 1 || $getExam['type_id'] == 3) { ?>
				<tr class="text-weight-semibold">
					<td valign="top" >GRAND TOTAL :</td>
					<td valign="top" colspan="<?=$colspan?>"><?=$grand_obtain_marks . '/' . $grand_full_marks; ?>, Average : <?php $percentage = ($grand_obtain_marks * 100) / $grand_full_marks; echo number_format($percentage, 2, '.', '')?>%</td>
				</tr>
				<tr class="text-weight-semibold">
					<td valign="top" >GRAND TOTAL IN WORDS :</td>
					<td valign="top" colspan="<?=$colspan?>">
						<?php
						$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
						echo ucwords($f->format($grand_obtain_marks));
						?>
					</td>
				</tr>
			<?php } if ($getExam['type_id'] == 2) { ?>
				<tr class="text-weight-semibold">
					<td valign="top" >GPA :</td>
					<td valign="top" colspan="<?=$colspan+1?>"><?=number_format(($total_grade_point / count($getMarksList)), 2, '.', '')?></td>
				</tr>
			<?php } if ($getExam['type_id'] == 3) { ?>
				<tr class="text-weight-semibold">
					<td valign="top" >GPA :</td>
					<td valign="top" colspan="<?=$colspan?>"><?=number_format(($total_grade_point / count($getMarksList)), 2, '.', '')?></td>
				</tr>
			<?php } if ($getExam['type_id'] == 1 || $getExam['type_id'] == 3) { ?>
				<tr class="text-weight-semibold">
					<td valign="top" >RESULT :</td>
					<td valign="top" colspan="<?=$colspan?>"><?=$result_status == 0 ? 'Fail' : 'Pass'; ?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		
		<div style="width: 100%; display: flex;">
			<div style="width: 50%; padding-right: 15px;">
				<?php
				if ($attendance == true) {
					$year = explode('-', $schoolYear);
					$getTotalWorking = $this->db->table('student_attendance')->where(array('student_id' => $studentID, 'status !=' => 'H', 'year(date)' => $year[0]))->get()->getNumRows();
					$getTotalAttendance = $this->db->table('student_attendance')->where(array('student_id' => $studentID, 'status' => 'P', 'year(date)' => $year[0]))->get()->getNumRows();
					$attenPercentage = empty($getTotalWorking) ? '0.00' : ($getTotalAttendance * 100) / $getTotalWorking;
					?>
				<table class="table table-bordered table-condensed">
					<tbody>
						<tr>
							<th colspan="2" class="text-center">Attendance</th>
						</tr>
						<tr>
							<th style="width: 65%;">No. of working days</th>
							<td><?=$getTotalWorking?></td>
						</tr>
						<tr>
							<th style="width: 65%;">No. of days attended</th>
							<td><?=$getTotalAttendance?></td>
						</tr>
						<tr>
							<th style="width: 65%;">Attendance Percentage</th>
							<td><?=number_format($attenPercentage, 2, '.', '') ?>%</td>
						</tr>
					</tbody>
				</table>
				<?php } ?>
			</div>
	<?php
	if ($grade_scale == true) {
		if ($getExam['type_id'] != 1) {
			?>
			<div style="width: 50%; padding-left: 15px;">
				<table class="table table-condensed table-bordered">
					<tbody>
						<tr>
							<th colspan="3" class="text-center">Grading Scale</th>
						</tr>
						<tr>
							<th>Grade</th>
							<th>Min Percentage</th>
							<th>Max Percentage</th>
						</tr>
					<?php 
					$grade = $this->db->table('grade')->where('branch_id', $getExam['branch_id'])->get()->getResultArray();
					foreach ($grade as $key => $row) {
					?>
						<tr>
							<td style="width: 30%;"><?=$row['name']?></td>
							<td style="width: 30%;"><?=$row['lower_mark']?>%</td>
							<td style="width: 30%;"><?=$row['upper_mark']?>%</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
	<?php } } ?>
		</div>
	<?php if (!empty($remarks_array[$sc])) { ?>
		<div style="width: 100%;">
			<table class="table table-condensed table-bordered">
				<tbody>
					<tr>
						<th style="width: 250px;">Remarks</th>
						<td><?=$remarks_array[$sc]?></td>
					</tr>
				</tbody>
			</table>
		</div>
	<?php } ?>
		<table style="width:100%; outline:none; margin-top: 35px;">
			<tbody>
				<tr>
					<td style="font-size: 15px; text-align:left;">Print Date : <?=_d($print_date)?></td>
					<td style="border-top: 1px solid #ddd; font-size:15px;text-align:left">Principal Signature</td>
					<td style="border-top: 1px solid #ddd; font-size:15px;text-align:center;">Class Teacher Signature</td>
					<td style="border-top: 1px solid #ddd; font-size:15px;text-align:right;">Parent Signature</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="pagebreak"> </div> 
<?php } } ?>
