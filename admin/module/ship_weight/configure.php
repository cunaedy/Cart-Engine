<?php
// this is the configuration file, you are free to create your own configuration editor
// See also: /admin/module/ship_weight/ini.xml & /module/ship_weight/window.php
$mod_id = 'ship_weight';
$cmd = get_param('cmd');
switch ($cmd) {
    case 'save':
        $display_name = get_param('display_name');
        $sc = get_param('sc');
        $ss = get_param('ss');
        $sy = get_param('sy');
        $sw = get_param('sw');
        $fee = array('city' => $sc, 'state' => $ss, 'country' => $sy, 'world' => $sw);
        $fee = serialize($fee);

        if (!empty($display_name)) {
            sql_query("UPDATE ".$db_prefix."module SET mod_name='$display_name' WHERE mod_id='$mod_id' LIMIT 1");
        }
        update_mod_config($mod_id, 'fee', $fee);
        admin_die('admin_ok');
    break;


    default:
        // uses module_ez_config template
        $tpl = load_tpl('var', $tpl_section['module_ez_config']);

        // load the configuration values
        $row = sql_qquery("SELECT * FROM ".$db_prefix."module WHERE mod_id='$mod_id' LIMIT 1");
        $fee = unserialize($module_config[$mod_id]['fee']);
        $sc = $fee['city'];
        $ss = $fee['state'];
        $sy = $fee['country'];
        $sw = $fee['world'];

        // init some stuffs
        $row['config_title'] = 'Shipping Module: By Weight';
        $row['mod_id'] = $mod_id;
        $row['hidden_values'] = create_hidden_form('what', 'module').create_hidden_form('mod_id', $row['mod_id']).create_hidden_form('cmd', 'save');

        // 1. configuration items
        $items = array(
            array('config_label' => 'Display name', 'config_value' => create_varchar_form('display_name', $row['mod_name'])),
            array('config_label' => 'Same city shipping fee (per '.$lang['l_weight_name'].')', 'config_value' => create_varchar_form('sc', $sc)),
            array('config_label' => 'Same state shipping fee (per '.$lang['l_weight_name'].')', 'config_value' => create_varchar_form('ss', $ss)),
            array('config_label' => 'Same country shipping fee (per '.$lang['l_weight_name'].')', 'config_value' => create_varchar_form('sy', $sy)),
            array('config_label' => 'International shipping fee (per '.$lang['l_weight_name'].')', 'config_value' => create_varchar_form('sw', $sw))
        );

        // 2. create block of items
        $row['block_configuration'] = '';
        foreach ($items as $k => $v) {
            $row['block_configuration'] .= quick_tpl($tpl_block['configuration'], $v);
        }

        // output
        $txt['main_body'] = quick_tpl($tpl, $row);
    break;
}
