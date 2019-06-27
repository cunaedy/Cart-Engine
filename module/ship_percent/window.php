<?php
$rate = $module_config['ship_percent']['fee'];
$ship_info = array();
$ship_info['method'] = $row_mod['mod_id'];
$ship_info['name'] = $row_mod['mod_name'];
$ship_info['fee'] = $summary['order_total'] * $rate / 100;
