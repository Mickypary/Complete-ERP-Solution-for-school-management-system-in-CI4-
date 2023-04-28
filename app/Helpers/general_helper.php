<?php

// return translation
function translate($word = '')
{
    $db = \Config\Database::connect();
    // code...
    if (session()->has('set_lang')) {
        $set_lang = session()->get('set_lang');
    }else {
        $set_lang = get_global_setting('translation');
    }

    if ($set_lang == '') {
        $set_lang = 'english';
    }

    $query = $db->table('languages')->where(['word' => $word])->get();
    if (count($query->getResult()) == 1) {
        if (isset($query->getRow()->$set_lang) && $query->getRow()->$set_lang != '') {
            return $query->getRow()->$set_lang;
        } else {
            return $query->getRow()->english;
        }
    }else {
        $arrayData = array(
            'word' => $word,
            'english' => ucwords(str_replace('_', ' ', $word)),
        );
        $db->table('languages')->insert($arrayData);
        return ucwords(str_replace('_', ' ', $word));
    }


}


// get date format config
function _d($date)
{
    if ($date == '' || is_null($date) || $date == '0000-00-00') {
        return '';
    }
    $formats = 'Y-m-d';
    $get_format = get_global_setting('date_format');
    if ($get_format != '') {
        $formats = $get_format;
    }
    return date($formats, strtotime($date));
}


// generate get image url
function get_image_url($role = '', $file_name = '')
{
    if ($file_name == 'defualt.png' || empty($file_name)) {
        $image_url = base_url('public/uploads/app_image/defualt.png');
    } else {
        if (file_exists('public/uploads/images/' . $role . '/' . $file_name)) {
            $image_url = base_url('public/uploads/images/' . $role . '/' . $file_name);
        } else {
            $image_url = base_url('public/uploads/app_image/defualt.png');
        }
    }
    return $image_url;
}

// get logged in user type
function get_loggedin_user_type()
{
    return session()->get('loggedin_type');
}

function is_secure($url)
{
    if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) {
        $val = 'https://' . $url;
    } else {
        $val = 'http://' . $url;
    }
    return $val;
}

// delete url
function csrf_jquery_token()
{
    $csrf = [csrf_token() => csrf_hash()];
    return $csrf;
}


function get_global_setting($name = '')
{
    $db = \Config\Database::connect();
    $name = trim($name);
    $query = $db->table('global_settings')->where(['id' => 1])->select($name)->get();
    if ($db->affectedRows() > 0) {
        $row = $query->getRow();
        return $row->$name;
    }

}

// is admin logged in @return boolean
function is_admin_loggedin()
{
    if (session()->get('loggedin_role_id') == 2) {
        return true;
    }
    return false;
}

function access_denied()
{
    set_alert('error', translate('access_denied'));
    return redirect()->to(site_url('dashboard'));
}


function get_session_id()
{
    if (session()->has('set_session_id')) {
        $session_id = session()->get('set_session_id');
    } else {
        $session_id = get_global_setting('session_id');
    }
    return $session_id;
}

// is superadmin logged in @return boolean
function is_superadmin_loggedin()
{
    if (session()->get('loggedin_role_id') == 1) {
        return true;
    }
    return false;
}

function get_permission($permission, $can = '')
{
    $role_id = session()->get('loggedin_role_id');
    if ($role_id == 1) {
        return true;
    }
    $permissions = get_staff_permissions($role_id);
    foreach ($permissions as $permObject) {
        if ($permObject->permission_prefix == $permission && $permObject->$can == '1') {
            return true;
        }
    }
    return false;
}

function get_staff_permissions($id)
{
    $db = \Config\Database::connect();
    $sql = "SELECT `staff_privileges`.*, `permission`.`id` as `permission_id`, `permission`.`prefix` as `permission_prefix` FROM `staff_privileges` JOIN `permission` ON `permission`.`id`=`staff_privileges`.`permission_id` WHERE `staff_privileges`.`role_id` = " . $db->escape($id);
    $result = $db->query($sql)->getResult();
    return $result;
}


// set session alert / flashdata
function set_alert($type, $message)
{
    session()->setFlashdata('alert-message-' . $type, $message);
}

// generate md5 hash
function app_generate_hash()
{
    return md5(rand() . microtime() . time() . uniqid());
}

// get logged in user type
function get_loggedin_branch_id()
{
    return session()->get('loggedin_branch');
}


// get session loggedin
function is_loggedin()
{
    if (session()->has('loggedin')) {
        return true;
    }
    return false;
}

// get table name by type and id
function get_type_name_by_id($table, $type_id = '', $field = 'name')
{
    $db = \Config\Database::connect();
    $get = $db->table($table)->select($field)->where('id', $type_id)->limit(1)->get()->getRowArray();
    return $get[$field];
}

function loggedin_name()
{
    if (session()->has('name')) {
        return session()->get('name');
    }
}

function loggedin_firstname()
{
    if (session()->has('firstname')) {
        return session()->get('firstname');
    }
}

// get staff db id
function get_loggedin_user_id()
{
    return session()->get('loggedin_userid');
}

function loggedin_role_id()
{
    return session()->get('loggedin_role_id');
}

// is parent logged in @return boolean
function is_parent_loggedin()
{
    if (session()->get('loggedin_role_id') == 6) {
        return true;
    }
    return false;
}

// is parent logged in @return boolean
function is_student_loggedin()
{
    if (session()->get('loggedin_role_id') == 7) {
        return true;
    }
    return false;
}

// get loggedin role name
function loggedin_role_name()
{
    $db = \Config\Database::connect();
    $roleID = session()->get('loggedin_role_id');
    return $db->table('roles')->where('id', $roleID)->select('name')->get()->getRow()->name;
}

// get logged in user id - login credential DB id
function get_loggedin_id()
{
    return session()->get('loggedin_id');
}

// delete url
function btn_delete($uri)
{
    return "<button class='btn btn-danger icon btn-circle' onclick=confirm_modal('" . base_url($uri) . "') ><i class='fas fa-trash-alt'></i></button>";
}

function ajax_access_denied()
{
    set_alert('error', translate('access_denied'));
    $array = array('status' => 'access_denied');
    echo json_encode($array);
    exit();
}


function slugify($text){
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '_', $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '_');

    // remove duplicated - symbols
    $text = preg_replace('~-+~', '_', $text);

    // lowercase
    $text = strtolower($text);
    return $text;
}