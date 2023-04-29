<?php 
namespace App\Libraries;
// use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\EmployeeModel;

class Csvimport
{
    private $handle = "";
    private $filepath = FALSE;
    private $column_headers = FALSE;
    private $initial_line = 0;
    private $delimiter = ",";
    private $detect_line_endings = FALSE;

    public $db;
    public $employee_model;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->employee_model = new EmployeeModel();
    }

    public function importCsvToDb($branchID,$userRole,$designationID,$departmentID,$newName)
    {
        $err_msg = "";
         $file = fopen(ROOTPATH ."public/csvfile/".$newName,"r");
                    $i = 0;
                    $numberOfFields = 12;
                    $csvArr = array();
                    
                    while (($filedata = fgetcsv($file, 0, $this->delimiter)) !== FALSE) {
                        if ($filedata[0] == NULL)
                        continue;
                        $num = count($filedata);
                        if($i > 0){ 
                            $csvArr[$i]['Name'] = $filedata[0];
                            $csvArr[$i]['Gender'] = $filedata[1];
                            $csvArr[$i]['Religion'] = $filedata[2];
                            $csvArr[$i]['BloodGroup'] = $filedata[3];
                            $csvArr[$i]['DateOfBirth'] = $filedata[4];
                            $csvArr[$i]['JoiningDate'] = $filedata[5];
                            $csvArr[$i]['Qualification'] = $filedata[6];
                            $csvArr[$i]['MobileNo'] = $filedata[7];
                            $csvArr[$i]['PresentAddress'] = $filedata[8];
                            $csvArr[$i]['PermanentAddress'] = $filedata[9];
                            $csvArr[$i]['Email'] = $filedata[10];
                            $csvArr[$i]['Password'] = $filedata[11];
                            // echo "<pre>";
                            // echo json_encode($filedata);
                            // echo json_encode($filedata[0]);
                        }
                        $i++;
                    }
                    fclose($file);
                    $count = 0;
                    foreach($csvArr as $row){

                        if (filter_var($row['Email'], FILTER_VALIDATE_EMAIL)) {
                            // verify existing username
                            $builder = $this->db->table('login_credential')->where('username', $row['Email']);
                            $query = $builder->get();
                            if ($query->getNumRows() > 0) {
                                $err_msg .= $row['Name'] . " - Imported Failed : Email Already Exists.<br>";
                            } else {
                                // save all employee information in the database
                                $this->employee_model->csvImport($row, $branchID, $userRole, $designationID, $departmentID);
                                // $i++;
                                $count++;
                            }
                        }else {
                            $err_msg .= $row['Name'] . " - Imported Failed : Invalid Email.<br>";
                        } /*End Validate Email*/

                    } // ENd Foreach

                    if ($err_msg != null) {
                        $msgRes = $count . ' Students Have Been Successfully Added. <br>';
                        $msgRes .= $err_msg;
                        echo json_encode(array('status' => 'errlist', 'errMsg' => $msgRes));
                        exit();
                    }
                    if ($count > 0) {
                        set_alert('success', $count . ' Students Have Been Successfully Added');
                    }

                    echo json_encode(array('status' => 'success'));        
    }
}