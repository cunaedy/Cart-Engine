<?php
$cmd = get_param('cmd');
$mod_id = 'pay_paypal';
switch ($cmd) {
    case 'save':
        $display_name = get_param('display_name');
        $be = get_param('be');
        $cc = get_param('cc');
        $ipn = get_param('ipn');
        $sb = get_param('sb');
        $cv = get_param('cv');
        $da = get_param('da');
        if (!empty($display_name)) {
            sql_query("UPDATE ".$db_prefix."module SET mod_name='$display_name' WHERE mod_id='$mod_id' LIMIT 1");
        }
        update_mod_config($mod_id, 'bussiness', $be);
        update_mod_config($mod_id, 'currency_code', $cc);
        update_mod_config($mod_id, 'ipn', $ipn);
        update_mod_config($mod_id, 'sandbox', $sb);
        update_mod_config($mod_id, 'conversion_rate', $cv);
        update_mod_config($mod_id, 'direct_approval', $da);

        admin_die('admin_ok');
    break;


    default:
        // uses module_ez_config template
        $tpl = load_tpl('var', $tpl_section['module_ez_config']);

        // load the configuration values
        $row = sql_qquery("SELECT * FROM ".$db_prefix."module WHERE mod_id='$mod_id' LIMIT 1");

        // init some stuffs
        $row['config_title'] = 'Payment Module: Paypal (IPN)';
        $row['mod_id'] = $mod_id;
        $row['hidden_values'] = create_hidden_form('what', 'module').create_hidden_form('mod_id', $row['mod_id']).create_hidden_form('cmd', 'save');

        // 1. configuration items
        $help = ' <span class="glyphicon glyphicon-info-sign help tips" title="If your main currency is not supported by Paypal, you can convert it to a PayPal supported currency by entering the conversion rate here. Eg: INR 0.016/USD. Otherwise, leave it empty or enter 1."></span> ';
        $items = array(
            array('config_label' => 'Display name', 'config_value' => create_varchar_form('display_name', $row['mod_name'])),
            array('config_label' => 'Bussiness email', 'config_value' => create_varchar_form('be', $module_config[$mod_id]['bussiness'])),
            array('config_label' => 'Currency code', 'config_value' => create_varchar_form('cc', $module_config[$mod_id]['currency_code'])),
            array('config_label' => 'Currency conversion rate', 'config_value' => $lang['l_cur_name'].' '.create_varchar_form('cv', $module_config[$mod_id]['conversion_rate'], 5).'/'.$module_config[$mod_id]['currency_code'].$help),
            array('config_label' => 'Enable IPN?', 'config_value' => create_radio_form('ipn', $yesno, $module_config[$mod_id]['ipn'])),
            array('config_label' => 'Enable Sandbox mode?', 'config_value' => create_radio_form('sb', $yesno, $module_config[$mod_id]['sandbox']))
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
