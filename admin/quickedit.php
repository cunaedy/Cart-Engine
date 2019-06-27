<?php
require './../includes/admin_init.php';
admin_check(4);

$cmd = get_param('cmd');
$p = get_param('p', 1);
$cat_id = get_param('cat_id');
$items = get_param('items');
if (empty($cmd)) {
    $cmd = post_param('cmd');
}

// init
$txt['cat_select'] = create_select_form('cat_id', $ce_cache['cat_structure'], $cat_id, '&nbsp;');
switch ($cmd) {
    case 'save':
        foreach ($_POST as $k => $v) {
            if (substr($k, 0, 8) == 'item_id_') {
                $item_id = substr($k, 8);
                $item_id = post_param('item_id_'.$item_id);
                $pr = post_param('price_'.$item_id);
                $st = post_param('stock_'.$item_id);
                sql_query("UPDATE ".$db_prefix."products SET price = '$pr', stock = '$st' WHERE idx='$item_id' LIMIT 1");
            }
        }
        admin_die('admin_ok');
    break;


    default:
        $tpl = load_tpl('adm', 'quickedit.tpl');
        $i = ($p - 1) * $config['list_ipp'];
        $txt['block_list'] = '';
        if ($items) {
            $foo = sql_multipage($db_prefix.'products', '*', "idx IN ($items)", 'sku', $p);
        } else {
            $foo = sql_multipage($db_prefix.'products', '*', "(cat_id='$cat_id') OR (add_category LIKE '%,$cat_id,%')", 'sku', $p);
        }
        foreach ($foo as $row) {
            $txt['block_list'] .= quick_tpl($tpl_block['list'], $row);
        }
        $txt['main_body'] = quick_tpl($tpl, $txt);
        flush_tpl('adm');
    break;
}
