<?php
$rate = $module_config['ship_free']['minimum'];
if (!empty($summary['all_digital'])) {
    $rate = 0;
}
if ($summary['order_total'] >= $rate) {
    $ship_info = array();
    $ship_info['method'] = $row_mod['mod_id'];
    $ship_info['name'] = $row_mod['mod_name'];
    $ship_info['fee'] = 0;
} else {
    $ship_info = false;
}
