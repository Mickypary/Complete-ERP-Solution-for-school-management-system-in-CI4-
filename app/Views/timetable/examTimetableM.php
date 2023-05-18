<?php 
use App\Models\ApplicationModel;

$this->application_model = new ApplicationModel();
$this->db = \Config\Database::connect();




$className = get_type_name_by_id('class', $class_id);
$sectionName = get_type_name_by_id('section', $section_id);
$academicYear = get_type_name_by_id('schoolyear', get_session_id(), 'school_year');
$getBranch = $this->db->table('branch')->where('id', $timetables->getRowArray()['branch_id'])->get()->getRowArray();
$examName = $this->application_model->exam_name_by_id($exam_id);
?>
<header class="panel-heading">
	<h4 class="panel-title">
		<i class="fas fa-list-ul"></i> <?=translate('schedule') . ' ' . translate('details')?>
	</h4>
	<div class="panel-btn">
		<button onclick="fn_printElem('printResult')" class="btn btn-default btn-circle">
			<i class="fas fa-print"></i> <?=translate('print')?>
		</button>
	</div>
</header>
<div class="panel-body">
	<p class="center text-dark">
		<?php
		echo '<strong>' . translate('exam') . ' : ' . $examName . '</strong><br>';
		echo translate('class') . ' : ' . $className  . '(' . $sectionName . ' )';
		?>
	</p>
	<hr class="solid-spc">
	<div class="table-responsive mb-md">
		<div id="printResult">
			<!-- hidden school information prints -->
			<div class="visible-print">
				<center>
					<h4 class="text-dark text-weight-bold"><?=$getBranch['name']?></h4>
					<h5 class="text-dark"><?=$getBranch['address']?></h5>
					<h5 class="text-dark text-weight-bold">Exam Schedule</h5>
					<h5 class="text-dark">
						<?php 
						echo translate('class') . ' : ' . $className . '(' . $sectionName . ') / ';
						echo translate('exam') . ' : ' . $examName;
						?>
					</h5>
					<h5 class="text-dark"><?php echo translate('academic_year') . " : " . $academicYear; ?></h5>
					<hr>
				</center>
			</div>
			<table class="table table-bordered table-hover table-condensed text-dark">
				<thead>
					<tr>
						<th><?=translate('subject')?></th>
						<th><?=translate('date')?></th>
						<th><?=translate('starting_time')?></th>
						<th><?=translate('ending_time')?></th>
						<th><?=translate('hall_room')?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(count($timetables->getResultArray())){
						foreach ($timetables->getResultArray() as $row):
					?>
					<tr>
					<td><?php echo $row['subject_name'];?></td>
					<td><?php echo _d($row['exam_date']);?></td>
					<td><?php echo $row['time_start'];?></td>
					<td><?php echo $row['time_end'];?></td>
					<td><?php echo $row['hall_no'];?></td>
					</tr>
					<?php
					endforeach;
					} else {
						echo '<tr> <td colspan="7"> <h5 class="text-danger text-center">' . translate('no_information_available') .  '</h5> </td></tr>';
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<footer class="panel-footer">
	<div class="text-right">
		<button class="btn btn-default modal-dismiss"><?=translate('close')?></button>
	</div>
</footer>
