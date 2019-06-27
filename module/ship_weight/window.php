<?php
$fee = unserialize($module_config['ship_weight']['fee']);

// default fee (international)
$rate = $fee['world'];

// country fee
if (comparecsn($config['site_country'], $user['ship_country'])) {
    $rate = $fee['country'];
}

// state fee
if (comparecsn($config['site_country'], $user['ship_country']) &&
   comparecsn($config['site_state'], $user['ship_state'])) {
    $rate = $fee['state'];
}

// city fee
if (comparecsn($config['site_country'], $user['ship_country']) &&
   comparecsn($config['site_state'], $user['ship_state']) &&
   comparecsn($config['site_city'], $user['ship_city'])) {
    $rate = $fee['city'];
}

$w = ceil($summary['order_weight']);
$ship_info = array();
$ship_info['method'] = $row_mod['mod_id'];
$ship_info['name'] = $row_mod['mod_name'];
$ship_info['fee'] = $w * $rate;
