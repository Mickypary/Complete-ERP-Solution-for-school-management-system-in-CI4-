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
		// print_r($result['exam']);

		$getExam = $this->db->table('exam')->where(array('id' => $examID))->get()->getRowArray();
		// print_r($getExam);
		// exit();
		$getSchool = $this->db->table('branch')->where(array('id' => $getExam['branch_id']))->get()->getRowArray();
		$schoolYear = get_type_name_by_id('schoolyear', $sessionID, 'school_year');
		?>
	<div  class="mark-container" style="font-size: 25px; font-family:georgia,garamond,serif; border: 5px solid #0f2df7;">
		<table cellpadding="10" border="1" bordercolor="#0f2df7" style="margin-top: 20px; height: 100px; border-width: thin; border-color: #0f2df7;">
			<tbody>
				<tr>
				<td valign="middle" style="width:20%;vertical-align: middle; text-align: center;"><img style="max-width:100px; text-align: center;" src="<?php echo base_url('public/uploads/app_image/report-card-logo.png');?>"></td>
				<td style="width:80%;vertical-align: top;">
					<table cellpadding="5" align="center" class="table-head text-right" style="margin-top: 30px;" >
						<tbody style="">
							<tr><th valign="middle" style="font-size: 40px; text-align: center;" class="text-right"><?=$getSchool['school_name']?></th></tr>
							<tr><th valign="middle" style="font-size: 20px; padding-top: 4px; text-align: center;" class="text-right">Academic Session : <?=$schoolYear?></th></tr>
							<tr><td valign="middle" style="font-size: 20px; text-align: center"><?=$getSchool['address']?></td></tr>
							<tr><td valign="middle" style="font-size: 20px; text-align: center;"><?=$getSchool['mobileno']?></td></tr>
							<tr><td valign="middle" style="font-size: 20px; text-align: center;"><?=$getSchool['email']?></td></tr>
						</tbody>

					</table>
					
				</td>
				<td>
					<?php $path =  basename(base_url('public/uploads/images/student/'. $student['photo'] ));?>
					<?php if($student['photo'] ==  $path):?>
					<img style="max-width:225px; text-align: center;" src="<?php echo base_url('public/uploads/images/student/'. $student['photo']);?>">
				<?php else: ?>
					<img style="max-width:225px; text-align: center;" src="<?php echo base_url('public/uploads/images/student/defualt.jpg');?>">
				<?php endif;  ?>
				</td>
				</tr>
			</tbody>
		</table>
		<table cellpadding="10" border="1" bordercolor="#0f2df7" class="table table-bordered" style="margin-top: 20px; font-family:georgia,garamond,serif; font-size: 20px; border-width: thin; border-color: #0f2df7;">
			<tbody>
				<tr>
					<th align="left">Name</td>
					<td><?=$student['first_name'] . " " . $student['last_name']?></td>
					<th align="left">Register No</td>
					<td><?=$student['register_no']?></td>
					<th align="left">Roll Number</td>
					<td><?=$student['roll']?></td>
				</tr>
				<tr>
					<th align="left">Father Name</td>
					<td><?=$student['father_name']?></td>
					<th align="left">Admission Date</td>
					<td><?=_d($student['admission_date'])?></td>
					<th align="left">Date of Birth</td>
					<td><?=_d($student['birthday'])?></td>
				</tr>
				<tr>
					<th align="left">Mother Name</td>
					<td><?=$student['mother_name']?></td>
					<th align="left">Class</td>
					<td><?=$student['class_name'] . " (" . $student['section_name'] . ")"?></td>
					<th align="left">Gender</td>
					<td><?=ucfirst($student['gender'])?></td>
				</tr>
			</tbody>
		</table>
		<table cellpadding="10" border="1" bordercolor="#0f2df7" class="table table-condensed table-bordered mt-lg" style="font-family:georgia,garamond,serif; font-size: 16px; border: 1px solid #0f2df7;">
			<thead>
				<tr>
					<th align="left"><?= strtoupper('Subjects') ?></th>
				<?php 
				$markDistribution = json_decode($getExam['mark_distribution'], true);
				foreach ($markDistribution as $id) {
					?>
					<th align="left"><?php echo strtoupper(get_type_name_by_id('exam_mark_distribution',$id))  ?></th>
				<?php } ?>
				<?php if ($getExam['type_id'] == 1) { ?>
					<th>Total</th>
				<?php } elseif($getExam['type_id'] == 2) { ?>
					<th>Grade</th>
					<th>Point</th>
				<?php } elseif ($getExam['type_id'] == 3) { ?>
					<th align="left"><?= strtoupper('Total') ?></th>
					<th align="left"><?= strtoupper('Grade') ?></th>
					<th align="left"><?= strtoupper('Remarks') ?></th>
					<th align="left"><?= strtoupper('GPA') ?></th>
				<?php } elseif($getExam['type_id'] == 4 || $getExam['type_id'] == 5) { ?>
					<th align="left"><?= strtoupper('MidTerm') ?></th>	
					<th align="left"><?= strtoupper('Total') ?></th>
					<!-- <th align="left"><?= strtoupper('1st Term') ?></th>						 -->
					<th align="left"><?= strtoupper('Grade') ?></th>
					<th align="left"><?= strtoupper('Remarks') ?></th>
					<th align="left"><?= strtoupper('GPA') ?></th>
				<?php } elseif($getExam['type_id'] == 6) { ?>
					<th align="left"><?= strtoupper('MidTerm') ?></th>	
					<th align="left"><?= strtoupper('Total') ?></th>					
					<th align="left"><?= strtoupper('1st Term') ?></th>					
					<th align="left"><?= strtoupper('2nd Term') ?></th>					
					<th align="left"><?= strtoupper('Grade') ?></th>
					<th align="left"><?= strtoupper('Remarks') ?></th>
					<th align="left"><?= strtoupper('GPA') ?></th>
				<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php
			$colspan = count($markDistribution) + 1;
			$total_grade_point = 0;
			$grand_obtain_marks = 0;
			$grand_obtain_xmas = 0;
			$grand_obtain_mid = 0;
			$grand_full_marks = 0;
			$result_status = 1;
			foreach ($getMarksList as $row) {
			?>
				<tr>
					<td valign="middle" width="35%"><?= strtoupper($row['subject_name'])?></td>
				<?php 
				$total_obtain_marks = 0;
				$total_obtain_mid = 0;
				$total_obtain_xmas = 0;
				$total_obtain_lent = 0;
				$total_xmas_mark = 0;
				$total_full_marks = 0;
				$fullMarkDistribution = json_decode($row['mark_distribution'], true);
				$obtainedMark = json_decode($row['get_mark'], true);
				$obtainedMid = json_decode($row['mark_mid'], true);
				$obtainedTotalXmas = json_decode($row['mark_xmas'], true);
				$obtainedTotalLent = json_decode($row['mark_lent'], true);
				$xmasTotal = json_decode($row['xmas_mid_total'], true);
				$lentTotal = json_decode($row['lent_mid_total'], true);
				print_r($xmasTotal);
				print_r($obtainedTotalXmas);

				foreach ($fullMarkDistribution as $i => $val) {
					$obtained_mark = isset($obtainedMark[$i]) ? floatval($obtainedMark[$i]) : '';
					$obtained_mid = isset($obtainedMid[$i]) ? floatval($obtainedMid[$i]) : '';
					$obtained_xmas = isset($obtainedTotalXmas[$i]) ? floatval($obtainedTotalXmas[$i]) : '';
					$obtained_lent = isset($obtainedTotalLent[$i]) ? floatval($obtainedTotalLent[$i]) : '';
					$xmas_total = isset($xmasTotal[$i]) ? floatval($xmasTotal[$i]) : '';
					$lent_total = isset($lentTotal[$i]) ? floatval($lentTotal[$i]) : '';

					if ($getExam['type_id'] == 4 || $getExam['type_id'] == 5 || $getExam['type_id'] == 6) {
							$total_obtain_marks +=  floatval($obtained_mark) + (number_format(floatval($obtained_mid),0)*0.2);
							$total_obtain_xmas += floatval($obtained_xmas) + number_format(floatval($xmas_total),0) *0.2;
							$total_obtain_lent += floatval($obtained_lent) + number_format(floatval($lent_total),0) *0.2;
					}elseif($getExam['type_id'] == 3) {
						$total_obtain_marks += $obtained_mark;
					}

					$fullMark = floatval($val['full_mark']);
					$passMark = floatval($val['pass_mark']);
					if ($obtained_mark < $passMark) {
						$result_status = 0;
					}


					// $total_obtain_marks += isset($obtained_mark) ? intval($obtained_mark) : 0;
					$total_obtain_mid += isset($obtained_mid) ? floatval(intval($obtained_mid) * 0.2) : 0;
					$obtained = $row['get_abs'] == 'on' ? 'Absent' : $obtained_mark;
					$total_full_marks += ($fullMark);
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
					<?php } if ($getExam['type_id'] == 4 || $getExam['type_id'] == 5 || $getExam['type_id'] == 6){ ?>
					<td valign="middle">
						<?php 
							if ($row['get_abs'] == 'on') {
								echo 'Absent';
							} else {
								// echo $obtained_mark;
								echo $obtained_mark . '/' . $fullMark;
							}
						?>
					</td>
					<?php
					}
				} // End Full Mark Dist  Foreach Loop

				// After this loop then we start adding the totals

				$grand_obtain_marks += $total_obtain_marks;
				$grand_obtain_xmas += $total_obtain_xmas;
									
									
				// $grand_full_marks += ($total_full_marks+20);
				if ($getExam['type_id'] == 4 || $getExam['type_id'] == 5 || $getExam['type_id'] == 6) {
					$grand_full_marks += ($total_full_marks+20);
				}elseif($getExam['type_id'] == 3) {
					$grand_full_marks += $total_full_marks;
				}
				?>

				<!-- TOTAL STARTING HERE -->
				<?php if($getExam['type_id'] == 1 || $getExam['type_id'] == 3) { ?>
					<td valign="middle"><?=number_format($total_obtain_marks, 0) . "/" . $total_full_marks?></td>
				<?php } if($getExam['type_id'] == 2) { 
					$percentage_grade = ($total_obtain_marks * 100) / $total_full_marks;
					$grade = $this->exam_model->get_grade($percentage_grade, $getExam['branch_id']);
					$total_grade_point += $grade['grade_point'];
					?>
					<td valign="middle"><?=$grade['name']?></td>
					<td valign="middle"><?=number_format($grade['grade_point'], 2, '.', '')?></td>

					<!-- For TypeID = 3  -->
					<?php } if ($getExam['type_id'] == 3) {
						$colspan += 2;
					$percentage_grade = ($total_obtain_marks * 100) / $total_full_marks;
					$grade = $this->exam_model->get_grade($percentage_grade, $getExam['branch_id']);
					$total_grade_point += isset($grade['grade_point']) ? $grade['grade_point'] : 0;
					?>
					
					<!-- For Grade Name -->
					<?php if($grade['name'] == "A*"): ?>
					<td valign="middle" style="color: green"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "A"): ?>
						<td valign="middle" style="color: blue"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "B"): ?>
						<td valign="middle" style="color: #A52A2A;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "C"): ?>
						<td valign="middle" style="color: #FFA500;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "D"): ?>
						<td valign="middle" style="color: #FFA07A;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "E"): ?>
						<td valign="middle" style="color: #800000;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "F"): ?>
						<td valign="middle" style="color: #DC143C;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php endif; ?>
					
					<!-- For Grade Remark -->
					<?php if($grade['name'] == "A*"): ?>
					<td valign="middle" style="color: green"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "A"): ?>
						<td valign="middle" style="color: blue"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "B"): ?>
						<td valign="middle" style="color: #A52A2A;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "C"): ?>
						<td valign="middle" style="color: #FFA500;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "D"): ?>
						<td valign="middle" style="color: #FFA07A;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "E"): ?>
						<td valign="middle" style="color: #800000;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "F"): ?>
						<td valign="middle" style="color: #DC143C;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php endif; ?>

					<td valign="middle"><?=number_format(isset($grade['grade_point']) ? $grade['grade_point'] : 0, 2, '.', '')?></td>

					<!-- For TypeID = 4 -->
					<?php } if($getExam['type_id'] == 4) { 
					$colspan += 2;
					$percentage_grade = ($total_obtain_marks) / ($total_full_marks+20) *100;
					$grade = $this->exam_model->get_grade($percentage_grade, $getExam['branch_id']);
					$total_grade_point += isset($grade['grade_point']) ? $grade['grade_point'] : 0;
					?>
					<td valign="middle"><?= $total_obtain_mid; ?></td>
					<td valign="middle"><?=number_format($total_obtain_marks,0,) . "/" . ($total_full_marks + 20)?></td>

					<!-- For Grade Name -->
					<?php if($grade['name'] == "A*"): ?>
					<td valign="middle" style="color: green"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "A"): ?>
						<td valign="middle" style="color: blue"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "B"): ?>
						<td valign="middle" style="color: #A52A2A;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "C"): ?>
						<td valign="middle" style="color: #FFA500;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "D"): ?>
						<td valign="middle" style="color: #FFA07A;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "E"): ?>
						<td valign="middle" style="color: #800000;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "F"): ?>
						<td valign="middle" style="color: #DC143C;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php endif; ?>

					<!-- For Grade Remark -->
					<?php if($grade['name'] == "A*"): ?>
					<td valign="middle" style="color: green"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "A"): ?>
						<td valign="middle" style="color: blue"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "B"): ?>
						<td valign="middle" style="color: #A52A2A;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "C"): ?>
						<td valign="middle" style="color: #FFA500;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "D"): ?>
						<td valign="middle" style="color: #FFA07A;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "E"): ?>
						<td valign="middle" style="color: #800000;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "F"): ?>
						<td valign="middle" style="color: #DC143C;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php endif; ?>


					<!-- <td valign="middle"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td> -->
					<td valign="middle"><?=number_format(isset($grade['grade_point']) ? $grade['grade_point'] : 0, 2, '.', '')?></td>

				<!-- </tr> -->

				<?php } if($getExam['type_id'] == 5) { 
					$colspan += 2;
					$percentage_grade = ($total_obtain_marks) / ($total_full_marks+20) *100;
					$grade = $this->exam_model->get_grade($percentage_grade, $getExam['branch_id']);
					$total_grade_point += isset($grade['grade_point']) ? $grade['grade_point'] : 0;
					?>
					<td valign="middle"><?= $total_obtain_mid; ?></td>
					<td valign="middle"><?=number_format($total_obtain_marks,0,) . "/" . ($total_full_marks + 20)?></td>
					<!-- <td valign="middle"><?=number_format($total_obtain_xmas,0,) . "/" . ($total_full_marks + 20)?></td> -->
					
					<!-- For Grade Name -->
					<?php if($grade['name'] == "A*"): ?>
					<td valign="middle" style="color: green"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "A"): ?>
						<td valign="middle" style="color: blue"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "B"): ?>
						<td valign="middle" style="color: #A52A2A;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "C"): ?>
						<td valign="middle" style="color: #FFA500;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "D"): ?>
						<td valign="middle" style="color: #FFA07A;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "E"): ?>
						<td valign="middle" style="color: #800000;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "F"): ?>
						<td valign="middle" style="color: #DC143C;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php endif; ?>
					
					<!-- For Grade Remark -->
					<?php if($grade['name'] == "A*"): ?>
					<td valign="middle" style="color: green"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "A"): ?>
						<td valign="middle" style="color: blue"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "B"): ?>
						<td valign="middle" style="color: #A52A2A;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "C"): ?>
						<td valign="middle" style="color: #FFA500;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "D"): ?>
						<td valign="middle" style="color: #FFA07A;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "E"): ?>
						<td valign="middle" style="color: #800000;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "F"): ?>
						<td valign="middle" style="color: #DC143C;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php endif; ?>

					<td valign="middle"><?=number_format(isset($grade['grade_point']) ? $grade['grade_point'] : 0, 2, '.', '')?></td>


				<?php } if($getExam['type_id'] == 6) { 
					$colspan += 2;
					$percentage_grade = ($total_obtain_marks) / ($total_full_marks+20) *100;
					$grade = $this->exam_model->get_grade($percentage_grade, $getExam['branch_id']);
					$total_grade_point += isset($grade['grade_point']) ? $grade['grade_point'] : 0;
					?>
					<td valign="middle"><?= $total_obtain_mid; ?></td>
					<td valign="middle"><?=number_format($total_obtain_marks,0,) . "/" . ($total_full_marks + 20)?></td>
					<td valign="middle"><?=number_format($total_obtain_xmas,0,) . "/" . ($total_full_marks + 20)?></td>
					<td valign="middle"><?=number_format($total_obtain_lent,0,) . "/" . ($total_full_marks + 20)?></td>
					
					<!-- For Grade Name -->
					<?php if($grade['name'] == "A*"): ?>
					<td valign="middle" style="color: green"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "A"): ?>
						<td valign="middle" style="color: blue"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "B"): ?>
						<td valign="middle" style="color: #A52A2A;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "C"): ?>
						<td valign="middle" style="color: #FFA500;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "D"): ?>
						<td valign="middle" style="color: #FFA07A;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "E"): ?>
						<td valign="middle" style="color: #800000;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php elseif($grade['name'] == "F"): ?>
						<td valign="middle" style="color: #DC143C;"><?=isset($grade['name']) ? $grade['name'] : ''?></td>
					<?php endif; ?>
					
					<!-- For Grade Remark -->
					<?php if($grade['name'] == "A*"): ?>
					<td valign="middle" style="color: green"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "A"): ?>
						<td valign="middle" style="color: blue"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "B"): ?>
						<td valign="middle" style="color: #A52A2A;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "C"): ?>
						<td valign="middle" style="color: #FFA500;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "D"): ?>
						<td valign="middle" style="color: #FFA07A;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "E"): ?>
						<td valign="middle" style="color: #800000;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php elseif($grade['name'] == "F"): ?>
						<td valign="middle" style="color: #DC143C;"><?=isset($grade['remark']) ? $grade['remark'] : ''?></td>
					<?php endif; ?>

					<td valign="middle"><?=number_format(isset($grade['grade_point']) ? $grade['grade_point'] : 0, 2, '.', '')?></td>

				</tr>
			<?php } }?>

			<?php if ($getExam['type_id'] == 1 || $getExam['type_id'] == 3) { ?>
				<tr class="text-weight-semibold">
					<td valign="top" >GRAND TOTAL :</td>
					<td valign="top" colspan="<?=$colspan?>"><?=$grand_obtain_marks . '/' . $grand_full_marks; ?>, Average : <?php $percentage = ($grand_obtain_marks * 100) / $grand_full_marks; echo number_format($percentage, 2, '.', '')?>%</td>
				</tr>
				<!-- <tr class="text-weight-semibold">
					<td valign="top" >GRAND TOTAL IN WORDS :</td>
					<td valign="top" colspan="<?=$colspan?>">
						<?php
						$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
						echo ucwords($f->format($grand_obtain_marks));
						?>
					</td>
				</tr> -->
			<?php } if ($getExam['type_id'] == 4 ) { ?>
				<tr class="text-weight-semibold">
						<td valign="top" >GRAND TOTAL :</td>
						<td valign="top" colspan="<?=$colspan?>"><?=$grand_obtain_marks . '/' . $grand_full_marks; ?>, Average : <?php $percentage = ($grand_obtain_marks * 100) / $grand_full_marks; echo number_format($percentage, 2, '.', '')?>%</td>
					</tr>
					<!-- <tr class="text-weight-semibold">
						<td valign="top" >GRAND TOTAL IN WORDS :</td>
						<td valign="top" colspan="<?=$colspan?>">
							<?php
							$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
							echo ucwords($f->format($grand_obtain_marks));
							?>
						</td>
					</tr> -->
			<?php } if ($getExam['type_id'] == 5 || $getExam['type_id'] == 6 ) { ?>
				<tr class="text-weight-semibold">
						<td valign="top" >GRAND TOTAL :</td>
						<td valign="top" colspan="<?=$colspan?>"><?=isset($total_obtain_marks) ? $total_obtain_marks : 0 . '/' . $grand_full_marks; ?>, Average : <?php $percentage = ($grand_obtain_marks * 100) / ($grand_full_marks+40); echo number_format($percentage, 2, '.', '')?>%</td>
					</tr>
					<!-- <tr class="text-weight-semibold">
						<td valign="top" >GRAND TOTAL IN WORDS :</td>
						<td valign="top" colspan="<?=$colspan?>">
							<?php
							$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
							echo ucwords($f->format($grand_obtain_marks));
							?>
						</td>
					</tr> -->
			<?php } if ($getExam['type_id'] == 2) { ?>
				<tr class="text-weight-semibold">
					<td valign="top" >CGPA :</td>
					<td valign="top" colspan="<?=$colspan+1?>"><?=number_format(($total_grade_point / count($getMarksList)), 2, '.', '')?></td>
				</tr>
			<?php } if ($getExam['type_id'] == 3 || $getExam['type_id'] == 4 || $getExam['type_id'] == 5 || $getExam['type_id'] == 6 ) { ?>
				<tr class="text-weight-semibold">
							<td valign="top" >CGPA :</td>
							<td valign="top" colspan="<?=$colspan?>"><?=number_format(($total_grade_point / count($getMarksList)), 2, '.', '')?></td>
						</tr>
			<?php } if ($getExam['type_id'] == 1 || $getExam['type_id'] == 3 || $getExam['type_id'] == 4) { ?>
				<tr class="text-weight-semibold">
					<td valign="top" >RESULT :</td>
					<td valign="top" colspan="<?=$colspan?>"><?=$result_status == 0 ? 'Fail' : 'Pass'; ?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		
		<div style="width: 100%; display: flex; border-width: thick; border-color: blueviolet !important;">
			<div style="width: 50%; padding-right: 15px;">
				<?php
				if ($attendance == true) {
					$year = explode('-', $schoolYear);
					$getTotalWorking = $this->db->table('student_attendance')->where(array('student_id' => $studentID, 'status !=' => 'H', 'year(date)' => $year[0]))->get()->getNumRows();
					$getTotalAttendance = $this->db->table('student_attendance')->where(array('student_id' => $studentID, 'status' => 'P', 'year(date)' => $year[0]))->get()->getNumRows();
					$attenPercentage = empty($getTotalWorking) ? '0.00' : ($getTotalAttendance * 100) / $getTotalWorking;
					?>
				<table cellpadding=10 border="1" bordercolor="#0f2df7" class="table table-bordered table-condensed">
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
				<table cellpadding="5" border="1" bordercolor="#0f2df7" class="table table-condensed table-bordered">
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

							<?php if($row['name'] == "A*"): ?>
							<td valign="middle" style="color: green; width: 30%"><?=isset($row['name']) ? $row['name'] : ''?></td>
							<?php elseif($row['name'] == "A"): ?>
								<td valign="middle" style="color: blue; width: 30%"><?=isset($row['name']) ? $row['name'] : ''?></td>
							<?php elseif($row['name'] == "B"): ?>
								<td valign="middle" style="color: #A52A2A; width: 30%"><?=isset($row['name']) ? $row['name'] : ''?></td>
							<?php elseif($row['name'] == "C"): ?>
								<td valign="middle" style="color: #FFA500; width: 30%"><?=isset($row['name']) ? $row['name'] : ''?></td>
							<?php elseif($row['name'] == "D"): ?>
								<td valign="middle" style="color: #FFA07A; width: 30%"><?=isset($row['name']) ? $row['name'] : ''?></td>
							<?php elseif($row['name'] == "E"): ?>
								<td valign="middle" style="color: #800000; width: 30%"><?=isset($row['name']) ? $row['name'] : ''?></td>
							<?php elseif($row['name'] == "F"): ?>
								<td valign="middle" style="color: #DC143C; width: 30%"><?=isset($row['name']) ? $row['name'] : ''?></td>
							<?php endif; ?>


							<td style="width: 30%;"><?=$row['lower_mark']?>%</td>
							<td style="width: 30%;"><?=$row['upper_mark']?>%</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
	<?php } } ?>
		</div>
	<?php if (!empty($teacher_remarks_array[$sc])) { ?>
		<?php

		$arrayMarks = array(
            'student_id' => $studentID,
            'exam_id' => $examID,
            'class_id' => $classID,
            'section_id' => $sectionID,
            'branch_id' => $branchID,
            'session_id' => $sessionID,
            // 'session_id' => get_session_id(),
        );

		$query = $this->db->table('remarks')->getWhere($arrayMarks);

		if ($query->getNumRows() > 0) {
	        $builder = $this->db->table('remarks')->where('id', $query->getRow()->id);

	        $builder->update(array('teacher_remarks' => $teacher_remarks_array[$sc], 'principal_remarks' => $principal_remarks_array[$sc]));
	    }else {
            $arrayMarks['teacher_remarks'] = $teacher_remarks_array[$sc];
            $arrayMarks['principal_remarks'] = $principal_remarks_array[$sc];
            $res = $this->db->table('remarks')->insert($arrayMarks);
        }

		?>
		<div style="width: 100%;">
			<table cellpadding="5" border="1" bordercolor="#0f2df7" class="table table-condensed table-bordered" style="font-family:georgia,garamond,serif;">
				<tbody>
					<br>
					<tr>
						<th style="width: 250px;">Teacher's Remarks</th>
						<td><?=$teacher_remarks_array[$sc]?></td>
					</tr>
					<tr>
						<th style="width: 250px;">Principal's Remarks</th>
						<td><?=$principal_remarks_array[$sc]?></td>
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
