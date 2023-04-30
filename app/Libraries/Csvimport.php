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

    // public $db;
    // public $employee_model;

    public function __construct()
    {
        // $this->db = \Config\Database::connect();
        // $this->employee_model = new EmployeeModel();
    }

    public function importCsvToDb($filepath=FALSE, $column_headers=FALSE, $detect_line_endings=FALSE, $initial_line=FALSE, $delimiter=FALSE,$newName='')
    {
        $err_msg = "";
         // $file = fopen(ROOTPATH ."public/csvfile/".$newName,"r");

        // File path
        if(! $filepath)
        {
            $filepath = $this->_get_filepath();    
        }
        else
        {   
            // If filepath provided, set it
            $this->_set_filepath($filepath);
        }

        // If file doesn't exists, return false
        if(! file_exists($filepath))
        {
            return FALSE;            
        }

        // Delimiter
        if(! $delimiter)
        {
            $delimiter = $this->_get_delimiter();    
        }
        else
        {   
            // If delimiter provided, set it
            $this->_set_delimiter($delimiter);
        }



         // $file = fopen($this->handle,"r");

         // Open the CSV for reading
        $this->_get_handle();
        // $file = fopen(ROOTPATH ."public/csvfile/".$newName,"r");
                    $i = 0;
                    $numberOfFields = 12;
                    $csvArr = array();
                    
                    while (($filedata = fgetcsv($this->handle, 0, $this->delimiter)) !== FALSE) {
                        if ($filedata[0] == NULL)
                        continue;
                        $num = count($filedata);
                        if($i > 0 && $num == $numberOfFields){ 
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
                            array_push($csvArr);
                        }
                        $i++;
                    }

                    // fclose($file);
                    $this->_close_csv();

                    return $csvArr;
                    
                          
    }


    /**
     * Gets the filepath of a given CSV file
     *
     * @access  private
     * @return  string
     */
    private function _get_filepath()
    {
        return $this->filepath;
    }

    /**
     * Sets the filepath of a given CSV file
     *
     * @access  private
     * @param   filepath    string  Location of the CSV file
     * @return  void
     */
    private function _set_filepath($filepath)
    {
        $this->filepath = $filepath;
    }

    /**
     * Opens the CSV file for parsing
     *
     * @access  private
     * @return  void
     */
    private function _get_handle()
    {
        $this->handle = fopen($this->filepath, "r");
    }

    /**
     * Closes the CSV file when complete
     *
     * @access  private
     * @return  array
     */
    private function _close_csv()
    {
        fclose($this->handle);
    }

    /**
     * Gets the values delimiter
     *
     * @access  private
     * @return  string
     */
    private function _get_delimiter()
    {
        return $this->delimiter;
    }

    /**
     * Sets the values delimiter
     *
     * @access  private
     * @param   initial_line    string  The values delimiter (eg. "," or ";")
     * @return  void
     */
    private function _set_delimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }  







} /*End Class */