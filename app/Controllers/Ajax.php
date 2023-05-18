<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\AjaxModel;
use App\Models\ApplicationModel;

/**
 * 
 */
class Ajax extends BaseController
{
	public $ajax_model;
	public $db;
    public $application_model;
	
	function __construct()
	{
		$this->ajax_model = new AjaxModel();
		$this->db = \Config\Database::connect();
        $this->application_model = new ApplicationModel();
	}

    // get exam list based on the branch
    public function getExamByBranch()
    {
        $html = "";
        $branchID = $this->application_model->get_branch_id();
        if (!empty($branchID)) {
            $builder = $this->db->table('exam')->select('id,name,term_id')
            ->where(array('branch_id' => $branchID, 'session_id' => get_session_id()));
            $result = $builder->get()->getResultArray();
            if (count($result)) {
                $html .= '<option value="">' . translate('select') . '</option>';
                foreach ($result as $row) {
                    if ($row['term_id'] != 0) {
                        $term = $this->db->table('exam_term')->select('name')->where('id', $row['term_id'])->get()->getRow()->name;
                        $name = $row['name'] . ' (' . $term . ')';
                    } else {
                        $name = $row['name'];
                    }
                    $html .= '<option value="' . $row['id'] . '">' . $name . '</option>';
                }
            } else {
                $html .= '<option value="">' . translate('no_information_available') . '</option>';
            }
        } else {
            $html .= '<option value="">' . translate('select_branch_first') . '</option>';
        }
        echo $html;
    }


    public function getClassByBranch()
    {
        $html = "";
        $branch_id = $this->application_model->get_branch_id();
        if (!empty($branch_id)) {
            $classes = $this->db->table('class')->select('id,name')->where('branch_id', $branch_id)->get()->getResultArray();
            if (count($classes)) {
                $html .= "<option value=''>" . translate('select') . "</option>";
                foreach ($classes as $row) {
                    $html .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                }
            } else {
                $html .= '<option value="">' . translate('no_information_available') . '</option>';
            }
        } else {
            $html .= '<option value="">' . translate('select_branch_first') . '</option>';
        }
        echo $html;
    }



    // get section list based on the class
    public function getSectionByClass()
    {
        $html = "";
        $classID = $this->request->getVar("class_id");
        $mode = $this->request->getVar("all");
        $multi = $this->request->getVar("multi");
        if (!empty($classID)) {
            if (loggedin_role_id() == 3) {
                $result = $this->db->table('teacher_allocation')->select('teacher_allocation.section_id,section.name')
                    ->join('section', 'section.id = teacher_allocation.section_id', 'left')
                    ->where(array('teacher_allocation.class_id' => $classID, 'teacher_allocation.teacher_id' => get_loggedin_user_id(), 'teacher_allocation.session_id' => get_session_id()))
                    ->get()->getResultArray();
            } else {
                $result = $this->db->table('sections_allocation')->select('sections_allocation.section_id,section.name')
                    ->join('section', 'section.id = sections_allocation.section_id', 'left')
                    ->where('sections_allocation.class_id', $classID)
                    ->get()->getResultArray();
            }
            if (count($result)) {
                if ($multi == false) {
                   $html .= '<option value="">' . translate('select') . '</option>';
                }
                if ($mode == true && loggedin_role_id() != 3) {
                    $html .= '<option value="all">' . translate('all_sections') . '</option>';
                }
                foreach ($result as $row) {
                    $html .= '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
                }
            } else {
                $html .= '<option value="">' . translate('no_selection_available') . '</option>';
            }
        } else {
            $html .= '<option value="">' . translate('select_class_first') . '</option>';
        }
        echo $html;
    }





	public function getDataByBranch()
    {
        $html = "";
        $table = $this->request->getVar('table');
        $branch_id = $this->application_model->get_branch_id();
        // $branch_id = $this->request->getVar('branch_id');
        if (!empty($branch_id)) {
            $result = $this->db->table($table)->select('id,name')->where('branch_id', $branch_id)->get()->getResultArray();
            if (count($result)) {
                $html .= "<option value=''>" . translate('select') . "</option>";
                foreach ($result as $row) {
                    $html .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                }
            } else {
                $html .= '<option value="">' . translate('no_information_available') . '</option>';
            }
        } else {
            $html .= '<option value="">' . translate('select_branch_first') . '</option>';
        }
        echo $html;
    }




	public function department_details()
    {
        if (get_permission('department', 'is_edit')) {
            // id in getVar is the ajax data request name in data object data{ id: id} where the first id is the request name
            $id = $this->request->getVar('id');
            $builder = $this->db->table('staff_department')->where('id', $id);
            $query = $builder->get();
            $result = $query->getRowArray();
            echo json_encode($result);
        }
    }

    public function designation_details()
    {
        if (get_permission('designation', 'is_edit')) {
            $id = $this->request->getVar('id');
            $builder = $this->db->table('staff_designation')->where('id', $id);
            $query = $builder->get();
            $result = $query->getRowArray();
            echo json_encode($result);
        }
    }


    public function getStafflistRole()
    {
        $html = "";
        $branch_id = $this->application_model->get_branch_id();
        if (!empty($branch_id)) {
            $role_id = $this->request->getVar('role_id');
            $selected_id = (isset($_POST['staff_id']) ? $_POST['staff_id'] : 0);
            $builder = $this->db->table('staff')->select('staff.id,staff.name,staff.staff_id,lc.role');
            $builder->join('login_credential as lc', 'lc.user_id = staff.id AND lc.role != 6 AND lc.role != 7', 'inner');
            $builder->where('lc.role', $role_id);
            $builder->where('staff.branch_id', $branch_id);
            $builder->orderBy('staff.id', 'asc');
            $result = $builder->get()->getResultArray();
            if (count($result)) {
                $html .= "<option value=''>" . translate('select') . "</option>";
                foreach ($result as $staff) {
                    $selected = ($staff['id'] == $selected_id ? 'selected' : '');
                    $html .= "<option value='" . $staff['id'] . "' " . $selected . ">" . $staff['name'] . " (" . $staff['staff_id'] . ")</option>";
                }
            } else {
                $html .= '<option value="">' . translate('no_information_available') . '</option>';
            }
        } else {
            $html .= '<option value="">' . translate('select_branch_first') . '</option>';
        }
        echo $html;
    }


    // get class assign modal
    public function getClassAssignM()
    {
        $classID = $this->request->getVar('class_id');
        $sectionID = $this->request->getVar('section_id');
        $branchID = get_type_name_by_id('class', $classID, 'branch_id');
        $html = "";
        $subjects = $this->db->table('subject')->getWhere(array('branch_id' => $branchID))->getResultArray();
        if (count($subjects)) {
            foreach ($subjects as $row) {
                $query_assign = $this->db->table('subject_assign')->getWhere(array(
                    'class_id' => $classID,
                    'section_id' => $sectionID,
                    'session_id' => get_session_id(),
                    'subject_id' => $row['id'],
                ));
                $html .= '<option value="' . $row['id'] . '"' . ($query_assign->getNumRows() != 0 ? 'selected' : '') . '>' . $row['name'] . '</option>';
            }
        }
        $data['branch_id'] = $branchID;
        $data['class_id'] = $classID;
        $data['section_id'] = $sectionID;
        $data['subject'] = $html;
        echo json_encode($data);
    }







} /*End Class */