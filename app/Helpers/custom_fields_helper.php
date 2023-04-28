<?php

use App\Models\ApplicationModel;




function render_custom_Fields($belongs_to, $branch_id = null, $edit_id = false, $col_sm = null)
{
    $db = \Config\Database::connect();
    $validation = \Config\Services::validation();

    $application_model = new ApplicationModel();
    if (empty($branch_id)) {
        $branch_id = $application_model->get_branch_id();
    }
    $builder = $db->table('custom_field');
    $builder->where('status', 1);
    $builder->where('form_to', $belongs_to);
    $builder->where('branch_id', $branch_id);
    $builder->orderBy('field_order','asc');
    $fields = $builder->get()->getResultArray();
    if (count($fields)) {
        $html = '';
        foreach ($fields as $field_key => $field) {
            $fieldLabel = ucfirst($field['field_label']);
            $fieldType = $field['field_type'];
            $bsColumn = $field['bs_column'];
            $required = $field['required'];
            $formTo = $field['form_to'];
            $fieldID = $field['id'];

            if ($bsColumn == '' || $bsColumn == 0) {
                $bsColumn == 12;
            }
            $value = $field['default_value'];

            if ($edit_id !== false) {
                $return = get_custom_field_value($edit_id, $fieldID, $formTo);
                if (!empty($return)) {
                    $value = $return;
                }
            }

            if(isset($_POST['custom_fields'][$formTo][$fieldID])) {
                $value = $_POST['custom_fields'][$formTo][$fieldID];
            }

            if ($fieldType != 'checkbox') {  
                $html .= '<div class="col-md-' . $bsColumn . ' mb-sm"><div class="form-group">';
                $html .= '<label class="control-label">' . $fieldLabel . ($required == 1 ? ' <span class="required">*</span>' : '') . '</label>';
                if ($fieldType == 'text' || $fieldType == 'number' || $fieldType == 'email') {
                    $html .= '<input type="' . $fieldType . '" class="form-control" name="custom_fields[' . $formTo . '][' . $fieldID . ']" value="' . $value . '" />';
                }
                if ($fieldType == 'textarea') {
                    $html .= '<textarea type="' . $fieldType . '" class="form-control" name="custom_fields[' . $formTo . '][' . $fieldID . ']">' . $value . '</textarea>';
                }
                if ($fieldType == 'dropdown') {
                    $html .= '<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="custom_fields[' . $formTo . '][' . $fieldID . ']">';
                    $html .= dropdownField($field['default_value'], $value);
                    $html .= '</select>';
                }
                if ($fieldType == 'date') {
                    $html .= '<input type="text" class="form-control" data-plugin-datepicker name="custom_fields[' . $formTo . '][' . $fieldID . ']" value="' . $value . '" />';
                }
                $html .= '<span class="error">' . $validation->getError('custom_fields[' . $formTo . '][' . $fieldID . ']') . '</span>';
                $html .= '</div></div>';
            } else {
                $html .= '<div class="col-md-' . $bsColumn . ' mb-sm"><div class="checkbox-replace">';
                $html .= '<label class="i-checks">';
                $html .= '<input type="checkbox" name="[' . $formTo . '][' . $fieldID . ']" value="1" ' . ($value == 1 ? 'checked' : '') . ' ><i></i>';
                $html .= $fieldLabel;
                $html .= '</label>';
                $html .= '</div></div>';
            }
        }
        return $html;
    } 
}



function dropdownField($default, $value)
{
    $options = explode(',', $default);
    $input = '<option value="">Select</option>';
    foreach ($options as $option_key => $option_value) {
        $input .= '<option value="' . slugify($option_value) . '" '. (slugify($option_value) == $value ? 'selected' : '') .'>' . ucfirst($option_value) . '</option>';
    }
    return $input;
}




function getCustomFields($belong_to)
{
	$db = \Config\Database::connect();

	$application_model = new ApplicationModel();

    $branchID = $application_model->get_branch_id();
    $builder = $db->table('custom_field');
    $builder->where('status', 1);
    $builder->where('form_to', $belong_to);
    $builder->where('branch_id', $branchID);
    $builder->orderBy('field_order','asc');
    $fields = $builder->get()->getResultArray();
    return $fields;
}

function saveCustomFields($post, $userID)
{
    $db = \Config\Database::connect();

    $arrayData = array();
    foreach ($post as $key => $value) {
        $insertData = array(
            'field_id' => $key, 
            'relid' => $userID, 
            'value' => $value, 
        );
        $builder = $db->table('custom_fields_values')->where('relid', $userID);
        $builder->where('field_id', $key);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $results = $query->getRow();
            $builder->where('id', $results->id);
            $builder->update($insertData);
        } else {
            $builder->insert($insertData);
        }
    }
}

function get_custom_field_value($rel_id, $field_id, $belongs_to)
{
    $db = \Config\Database::connect();

    $builder = $db->table('custom_field')->select('custom_fields_values.value');
    $builder->join('custom_fields_values', 'custom_fields_values.field_id = custom_field.id and custom_fields_values.relid = ' . $rel_id, 'inner');
    $builder->where('custom_field.form_to', $belongs_to);
    $builder->where('custom_fields_values.field_id', $field_id);
    $row = $builder->get()->getRowArray();
    return $row['value'];
}


function custom_form_table($belong_to, $branch_id)
{
    $db = \Config\Database::connect();

    $builder = $db->table('custom_field');
    $builder->where('status', 1);
    $builder->where('form_to', $belong_to);
    $builder->where('show_on_table', 1);
    $builder->where('branch_id', $branch_id);
    $builder->orderBy('field_order','asc');
    $fields = $builder->get()->getResultArray();
    return $fields;
}


function get_table_custom_field_value($field_id, $rel_id)
{
    $db = \Config\Database::connect();

    $builder = $db->table('custom_fields_values');
    $builder->where('relid', $rel_id);
    $builder->where('field_id', $field_id);
    $row = $builder->get()->getRowArray();
    return $row['value'];
}