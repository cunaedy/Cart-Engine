<?php
$cmd = get_param('cmd');
$mod_id = 'pay_bank';
switch ($cmd) {
    case 'save':
        $display_name = get_param('display_name');
        $bn = get_param('bn');
        $ba = get_param('ba');
        $bc = get_param('bc');
        $ac = get_param('ac');
        $ah = get_param('ah');
        if (!empty($display_name)) {
            sql_query("UPDATE ".$db_prefix."module SET mod_name='$display_name' WHERE mod_id='$mod_id' LIMIT 1");
        }
        update_mod_config($mod_id, 'bankname', $bn);
        update_mod_config($mod_id, 'bankaddress', $ba);
        update_mod_config($mod_id, 'bankcode', $bc);
        update_mod_config($mod_id, 'account', $ac);
        update_mod_config($mod_id, 'holder', $ah);
        admin_die('admin_ok');
    break;


    default:
        // uses module_ez_config template
        $tpl = load_tpl('var', $tpl_section['module_ez_config']);

        // load the configuration values
        $row = sql_qquery("SELECT * FROM ".$db_prefix."module WHERE mod_id='$mod_id' LIMIT 1");

        // init some stuffs
        $row['config_title'] = 'Payment Module: Bank Wire Transfer';
        $row['mod_id'] = $mod_id;
        $row['hidden_values'] = create_hidden_form('what', 'module').create_hidden_form('mod_id', $row['mod_id']).create_hidden_form('cmd', 'save');

        // 1. configuration items
        $items = array(
            array('config_label' => 'Display name', 'config_value' => create_varchar_form('display_name', $row['mod_name'])),
            array('config_label' => 'Bank name', 'config_value' => create_varchar_form('bn', $module_config[$mod_id]['bankname'])),
            array('config_label' => 'Bank address', 'config_value' => create_varchar_form('ba', $module_config[$mod_id]['bankaddress'])),
            array('config_label' => 'Bank code', 'config_value' => create_varchar_form('bc', $module_config[$mod_id]['bankcode'])),
            array('config_label' => 'Account number', 'config_value' => create_varchar_form('ac', $module_config[$mod_id]['account'])),
            array('config_label' => 'Account holder', 'config_value' => create_varchar_form('ah', $module_config[$mod_id]['holder']))
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
