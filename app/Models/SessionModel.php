<?php 

namespace App\Models;

use CodeIgniter\Model;

/**
 * 
 */
class SessionModel extends Model
{
	
	protected $DBGroup = 'default';
    protected $table = 'schoolyear';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    // protected $allowedFields = ['std_name','std_phone','std_surname','std_dp','created_at','updated_at','deleted_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    // protected $deletedField = 'deleted_at';
    
    protected $validateRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    protected $beforeUpdate = ['unique_name'];
}