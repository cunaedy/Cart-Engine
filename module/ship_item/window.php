<?php
$rate = $module_config['ship_item']['per_item'];
$ship_info = array();
$ship_info['method'] = $row_mod['mod_id'];
$ship_info['name'] = $row_mod['mod_name'];
$ship_info['fee'] = $rate * $summary['order_items'];
