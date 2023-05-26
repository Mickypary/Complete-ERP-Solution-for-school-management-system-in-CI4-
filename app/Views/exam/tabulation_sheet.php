<?php
use App\Libraries\App_lib;
use App\Models\ApplicationModel;
use App\Models\ExamModel;

$this->app_lib = new App_lib();
$this->db = \Config\Database::connect();
$this->application_model = new ApplicationModel();
$this->exam_model = new ExamModel();






$widget = (is_superadmin_loggedin() ? 2 : 3);
$branch = $this->db->table('branch')->where('id',$branch_id)->get()->getRowArray();
?>
<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<?php echo form_open('exam/tabulation_sheet', array('class' => 'validate')); ?>
			<header class="panel-heading">
				<h4 class="panel-title"><?=translate('select_ground')?></h4>
			</header>
			<div class="panel-body">
				<div class="row mb-sm">
				<?php if (is_superadmin_loggedin() ): ?>
					<div class="col-md-2 mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id' required
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
						</div>
					</div>
				<?php endif; ?>
					<div class="col-md-<?=$widget?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('academic_year')?> <span class="required">*</span></label>
							<?php
								$arrayYear = array("" => translate('select'));
								$years = $this->db->table('schoolyear')->get()->getResult();
								foreach ($years as $year){
									$arrayYear[$year->id] = $year->school_year;
								}
								echo form_dropdown("session_id", $arrayYear, set_value('session_id', get_session_id()), "class='form-control' required
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="col-md-<?=$widget?> mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('exam')?> <span class="required">*</span></label>
							<?php
								
								if(!empty($branch_id)){
									$arrayExam = array("" => translate('select'));
									$exams = $this->db->table('exam')->getWhere(array('branch_id' => $branch_id,'session_id' => get_session_id()))->getResult();
									foreach ($exams as $exam){
										$arrayExam[$exam->id] = $this->application_model->exam_name_by_id($exam->id);
									}
								} else {
									$arrayExam = array("" => translate('select_branch_first'));
								}
								echo form_dropdown("exam_id", $arrayExam, set_value('exam_id'), "class='form-control' id='exam_id' required
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>

					<div class="col-md-2 mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('exam_type_rel')?> <span class="required">*</span></label>
							<?php
								// $arrayClass = $this->app_lib->getExamType($branch_id);
							$arrayTypes = array("" => translate('select'));
								$builder = $this->db->table('exam');
                				$result = $builder->get()->getResult();
                				// print_r($result);
                				foreach ($result as $row){
										// $arrayType[$row->id] = $row->type_id;
										if ($row->type_id == 4) {
											$arrayTypes[$row->type_id] = 'End Of Xmas Term';

										}elseif($row->type_id == 3) {
											$arrayTypes[$row->type_id] = 'Mid';
										}elseif($row->type_id == 5) {
											$arrayTypes[$row->type_id] = 'End Of Lent Term';
										}elseif ($row->type_id == 6) {
											$arrayTypes[$row->type_id] = 'End Of 3rd Term';
										}
									}
								echo form_dropdown("type_rel_id", $arrayTypes, set_value('type_rel_id'), "class='form-control' 
								required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>

					<div class="col-md-2 mb-sm">
						<div class="form-group">
							<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
							<?php
								$arrayClass = $this->app_lib->getClass($branch_id);
								echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)'
								required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>

					<div class="col-md-<?=$widget?>">
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
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-offset-10 col-md-2">
						<button type="submit" name="submit" value="search" class="btn btn-default btn-block"><i class="fas fa-filter"></i> <?=translate('filter')?></button>
					</div>
				</div>
			</div>
			<?php echo form_close();?>
		</section>

		<?php if (isset($get_subjects)) { ?>
			<section class="panel appear-animation" data-appear-animation="<?php echo $global_config['animations'];?>" data-appear-animation-delay="100">
				<header class="panel-heading">
					<h4 class="panel-title">
						<i class="fas fa-users"></i> <?=translate('tabulation_sheet')?>
					</h4>
				</header>
				<div class="panel-body">
					<div class="table-responsive mt-sm mb-md">
						<div id="printResult">
							<!-- hidden school information prints -->
							<div class="visible-print">
								<center>
									<h4 class="text-dark text-weight-bold"><?=$branch['name']?></h4>
									<h5 class="text-dark"><?=$branch['address']?></h5>
									<h5 class="text-dark text-weight-bold"><?=$this->application_model->exam_name_by_id(set_value('exam_id'))?> - Tabulation Sheet</h5>
									<h5 class="text-dark">
										<?php 
										echo translate('class') . ' : ' . get_type_name_by_id('class', set_value('class_id'));
										echo ' ( ' . translate('section') . ' : ' . get_type_name_by_id('section', set_value('section_id')) . ' )';
										?>
									</h5>
									<hr>
								</center>
							</div>
							<table class="table table-bordered table-hover table-condensed mb-none">
								<thead class="text-dark">
									<tr>
										<td><?=translate('sl')?></td>
										<td><?=translate('students')?></td>
										<td><?=translate('roll')?></td>
                                        <?php
                                            foreach($get_subjects->getResultArray() as $subject){
                                            	$fullMark = array_sum(array_column(json_decode($subject['mark_distribution'], true), 'full_mark'));
                                                echo '<td>' . $subject['subject_name'] . " (" . (($type_rel_id==4 || $type_rel_id==5 || $type_rel_id==6) ? $fullMark+20 : $fullMark) . ')</td>';
                                            }
                                        ?>
										<td><?=translate('total_marks')?></td>
										<td>GPA</td>
										<td><?=translate('result')?></td>
									</tr>
								</thead>
								<tbody>
									<?php
									$count = 1;
									$enrolls = $this->db->table('enroll')->getWhere(array(
										'class_id' 		=> set_value('class_id'),
										'section_id' 	=> set_value('section_id'),
										'session_id' 	=> set_value('session_id'),
										'branch_id' 	=> $branch_id,
									))->getResultArray();
									if(count($enrolls)) {
										foreach($enrolls as $enroll):
											$stu = $this->db->table('student')->select('CONCAT(first_name, " ", last_name) as fullname')
											->where('id', $enroll['student_id'])
											->get()->getRowArray();
											?>
									<tr>
										<td><?php echo $count++; ?></td>
										<td><?php echo $stu['fullname']; ?></td>
										<td><?php echo $enroll['roll']; ?></td>
										<?php
										$totalMarks 		= 0;
										$totalFullmarks 	= 0;
										$totalGradePoint 	= 0;
										$grand_result 		= 0;
										$unset_subject 		= 0;
										foreach ($get_subjects->getResultArray() as $subject):
											$result_status = 1;
											?>
										<td>
										<?php
											$builder = $this->db->table('mark as m')->select('m.*, mr.*')
											->join('mark_rel as mr', 'mr.student_id = m.student_id and mr.subject_id = m.subject_id', 'left')
											->where(array(
												'm.class_id' 	 => set_value('class_id'),
												'm.exam_id'	 => set_value('exam_id'),
												'm.subject_id' => $subject['subject_id'],
												'm.student_id' => $enroll['student_id'],
												'm.session_id' => set_value('session_id'),
												// 'm.type_id' => set_value('type_rel_id'),
											));
											$getMark = $builder->get()->getRowArray();
											if (!empty($getMark)) {
												if ($getMark['absent'] != 'on') {
													$totalObtained = 0;
													$totalObtainedMid = 0;
													$totalFullMark = 0;
													$fullMarkDistribution = json_decode($subject['mark_distribution'], true);
													$obtainedMark = json_decode($getMark['mark'], true);
													$obtainedMidMark = json_decode($getMark['mark_mid'], true);
													// print_r($obtainedMark);
													foreach ($fullMarkDistribution as $i => $val) {
														$obtained_mark = floatval($obtainedMark[$i]);
														$obtained_mid_mark = floatval($obtainedMidMark[$i]);

														// Add MidTerm Score to TotalObtained
														// $totalObtained += $obtained_mark;
														if ($type_rel_id == 4 || $type_rel_id == 5 || $type_rel_id == 6) {
															$totalObtained += ($obtained_mark + ($obtained_mid_mark*0.2));
														}elseif($type_rel_id == 3) {
															$totalObtained += $obtained_mark;
														}
														$totalObtainedMid += $obtained_mid_mark;
														$totalFullMark += $val['full_mark'];
														$passMark = floatval($val['pass_mark']);
														if ($obtained_mark < $passMark) {
															$result_status = 0;
														}
														// print_r($obtained_mark);
														
													} // End Foreach for $fullMarkDist


													// echo ($total_obtain_marks  . "/" . $totalFullMark);
													if ($type_rel_id == 4 || $type_rel_id == 5 || $type_rel_id == 6) {
														echo (($totalObtained)  . "/" . ($totalFullMark+20));
													}elseif($type_rel_id == 3) {
														echo ($totalObtained  . "/" . $totalFullMark);
													}

													if ($totalObtained != 0 && !empty($totalObtained)) {
														$grade = $this->exam_model->get_grade($totalObtained, $branch_id);
														$totalGradePoint += $grade['grade_point'];
													}
													$totalMarks += $totalObtained;
												} else {
													echo translate('absent');
												}
												$totalFullmarks += $totalFullMark;
											} else {
												echo "N/A";
												$unset_subject++;
											}
										?>
										</td>
										<?php endforeach; ?>
										<!-- <td><?php echo ($totalMarks . '/' . $totalFullmarks); ?></td> -->
										<?php if ($type_rel_id == 4 || $type_rel_id == 5 || $type_rel_id == 6):?>
											<td><?php echo ($totalMarks . '/' . ($totalFullmarks+60)); ?></td>
										<?php elseif($type_rel_id == 3): ?>
											<td><?php echo ($totalMarks . '/' . $totalFullmarks); ?></td>
										<?php endif; ?>

										<td>
											<?php
											// $totalSubjects = count($get_subjects);
											$totalSubjects = count($get_subjects->getResultArray());
											if(!empty($totalSubjects)) {
												echo number_format(($totalGradePoint / $totalSubjects), 2,'.','');
											}
											?>
										</td>
										<td>
										<?php
											if ($unset_subject == 0) {
												if (empty($result_status)) {
													echo '<span class="label label-primary">PASS</span>';
												} else {
													echo '<span class="label label-danger">FAIL</span>';
												}
											}
										?>
										</td>
									</tr>
									<?php
									endforeach;
									}else{
										$colspan = ($get_subjects->getNumRows() + 5);
										echo '<tr><td colspan="' . $colspan . '"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-offset-10 col-md-2">
							<button onclick="fn_printElem('printResult')" class="btn btn-default btn-sm btn-block">
								<i class="fas fa-print"></i> <?=translate('print')?>
							</button>
						</div>
					</div>
				</footer>
			</section>
	<?php } ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$('#branch_id').on("change", function() {
			var branchID = $(this).val();
			getClassByBranch(branchID);
			getExamByBranch(branchID);
		});
	});
</script>
