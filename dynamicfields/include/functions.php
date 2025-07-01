<?php
function getCustomFields($module_dirname) {
    $module_dirname = $GLOBALS['xoopsDB']->escape($module_dirname);
    $sql = "SELECT * FROM " . $GLOBALS['xoopsDB']->prefix('dynamicfields_fields') . 
           " WHERE module_dirname = '$module_dirname'";
    $result = $GLOBALS['xoopsDB']->query($sql);
    $fields = [];
    while ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
        $fields[] = $row;
    }
    return $fields;
}

function addCustomFieldsToForm(&$form, $module_dirname) {
    $fields = getCustomFields($module_dirname);
    foreach ($fields as $field) {
        switch ($field['field_type']) {
            case 'text':
                $form->addElement(new XoopsFormText($field['field_label'], 'custom_' . $field['field_name'], 50, 255, '', $field['field_required']));
                break;
            case 'textarea':
                $form->addElement(new XoopsFormTextArea($field['field_label'], 'custom_' . $field['field_name'], '', 5, 50, $field['field_required']));
                break;
            case 'select':
                $select = new XoopsFormSelect($field['field_label'], 'custom_' . $field['field_name'], null, 1, false);
                $select->setExtra($field['field_required'] ? 'required' : '');
                $form->addElement($select);
                break;
        }
    }
}
?>