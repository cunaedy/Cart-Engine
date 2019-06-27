<?php
require './../includes/admin_init.php';
require './../includes/checkout_lib.php';
admin_check('site_setting');

//
$ship_method = array(0 => 'Flat', 1 => 'Increasing');
$order_format = array(1 => '1 - Default (eg CE000095)', 2 => '2 - Randomized (eg CEAB832B)');
$tax_base_def = array('billing' => 'Billing Address', 'shipping' => 'Shipping Address');
$ship_area_def = array(
    'local' => "Citywide, ie. I only ship inside $config[site_city].",
    'state' => "Statewide, ie. I only ship inside $config[site_state].",
    'nation' => "Nationwide, ie. I ship to anywhere within $config[site_country].",
    'world' => 'Worldwide, ie. I ship internationally.');
$price_tax_def = array('hide' => 'Hide tax until checkout', 'separate' => 'Display separately', 'merge' => 'Add tax to price directly');

//
$res = sql_query("SELECT * FROM ".$db_prefix."config WHERE group_id='cart'");
while ($row = sql_fetch_array($res)) {
    $cfg[$row['config_id']] = $row['config_value'];
}
$tpl = load_tpl('adm', 'local_config.tpl');

// taxes
$cfg['block_tax_list'] = '';
$foo = get_editable_option('tax');
foreach ($foo as $k => $v) {
    $row = get_tax_rate($k);
    $row['tidx'] = $k;
    $row['tax_title'] = $v;
    $cfg['block_tax_list'] .= quick_tpl($tpl_block['tax_list'], $row);
}

// others
$cfg['area_select'] = create_radio_form('ship_area', $ship_area_def, $cfg['ship_area'], 'v');
$cfg['display_cart_select'] = create_radio_form('buy_display_cart', $yesno, $cfg['buy_display_cart']);
$cfg['stock_select'] = create_select_form('manage_stock', $enabledisable, $cfg['manage_stock']);
$cfg['order_id_select'] = create_select_form('order_id_format', $order_format, $cfg['order_id_format']);
$cfg['price_tax_select'] = create_select_form('display_price_tax', $price_tax_def, $cfg['display_price_tax']);
$cfg['tax_base_select'] = create_select_form('tax_base', $tax_base_def, $cfg['tax_base']);
$cfg['express_radio'] = create_radio_form('allow_xpress', $yesno, $cfg['allow_xpress']);
$cfg['hide_ship_radio'] = create_radio_form('hide_ship', $yesno, $cfg['hide_ship']);
$txt['main_body'] = quick_tpl($tpl, $cfg);
flush_tpl('adm');
