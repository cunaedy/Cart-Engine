<?php
require './../includes/admin_init.php';
admin_check('site_setting');
AXSRF_check();

// demo mode?
if ($config['demo_mode']) {
    admin_die('demo_mode');
}

// exclusion
$excluded = array('featured_product', 'xc_version', 'price_step');
// get param
$res = sql_query("SELECT * FROM ".$db_prefix."config WHERE group_id='cart'");
while ($row = sql_fetch_array($res)) {
    ${$row['config_id']} = post_param($row['config_id'], '', 'html');
}

// escape some html
$cat_separator = post_param('cat_separator', '', 'html');
if (empty($cat_separator)) {
    $cat_separator = '&bull;';
}

// currency flags
for ($i = 0; $i <= 5; $i++) {
    if (!empty($_FILES['curr_flag_'.$i]['tmp_name'])) {
        $f = pathinfo($_FILES['curr_flag_'.$i]['name']);
        $tgt = "../public/image/curr_$i.gif";
        upload_file('curr_flag_'.$i, $tgt, true);

        $cfg['curr_symbol_'.$i] = post_param('curr_symbol_'.$i, '', 'html');
    }
}

// update db
$res = sql_query("SELECT * FROM ".$db_prefix."config WHERE group_id='cart'");
while ($row = sql_fetch_array($res)) {
    if (!in_array($row['config_id'], $excluded)) {
        sql_query("UPDATE ".$db_prefix."config SET config_value='{${$row['config_id']}}' WHERE config_id='$row[config_id]' LIMIT 1");
    }
}

// taxes
$foo = get_editable_option('tax');
foreach ($foo as $k => $v) {
    $tax = array(
        'city' => post_param('tax_city_'.$k),
        'state' => post_param('tax_state_'.$k),
        'nation' => post_param('tax_nation_'.$k),
        'world' => post_param('tax_world_'.$k));
    $tax = addslashes(serialize($tax));
    $j = sql_qquery("SELECT * FROM ".$db_prefix."config WHERE group_id='var' AND config_id='tax_$k' LIMIT 1");
    if ($j) {
        sql_query("UPDATE ".$db_prefix."config SET config_value='$tax' WHERE idx='$j[idx]' LIMIT 1");
    } else {
        sql_query("INSERT INTO ".$db_prefix."config SET group_id='var', config_id='tax_$k', config_value='$tax'");
    }
}

// rebuild cache!
qcache_clear('everything');
sql_query("UPDATE ".$db_prefix."language SET lang_value='' WHERE lang_key='_config:cache' LIMIT 1");

admin_die('admin_ok');
