<?php
// part of qEngine
require './../includes/admin_init.php';
admin_check(4);
$cmd = get_param('cmd');
$source_cat = get_param('source_cat');
$target_cat = get_param('target_cat');
$show_all = get_param('show_all');

switch ($cmd) {
    case 'save':
        if (empty($target_cat)) {
            admin_die('Please select a target category.');
        }
        if (!array_key_exists($target_cat, $ce_cache['cat_name_def'])) {
            admin_die('Please select a target category.');
        }
        $items = checkbox_param('items', 'get', true);
        foreach ($items as $k => $v) {
            sql_query("UPDATE ".$db_prefix."products SET cat_id='$target_cat' WHERE idx='$v' LIMIT 1");
        }
        admin_die('admin_ok');
    break;


    default:
        if (empty($ce_cache['cat_structure'])) {
            admin_die('No categories defined. Please create one first!');
        }
        $source_def = array(999999 => '(Orphaned)') + $ce_cache['cat_structure'];
        $cat_id_list = implode(',', array_keys($ce_cache['cat_name_def']));
        $tpl = load_tpl('adm', 'cat_mover.tpl');
        $txt['block_list'] = '';
        $txt['source_cat'] = create_select_form('source_cat', $source_def, $source_cat);
        $txt['target_cat'] = create_select_form('target_cat', $ce_cache['cat_structure'], '', '&nbsp;');

        if (!empty($source_cat)) {
            $list = array();
            if ($source_cat == 999999) {
                $res = sql_query("SELECT idx, title FROM ".$db_prefix."products WHERE (cat_id='999999') OR (cat_id NOT IN($cat_id_list)) ORDER BY title");
            } else {
                $res = sql_query("SELECT idx, title FROM ".$db_prefix."products WHERE cat_id='$source_cat' ORDER BY title");
            }
            while ($row = sql_fetch_array($res)) {
                $list[$row['idx']] = "$row[title] &mdash; <a href=\"product.php?mode=edit&amp;item_id=$row[idx]\" target=\"editor\">view</a>";
            }

            $txt['product_list'] = create_checkbox_form('items', $list, '', 1, 'list');
        } else {
            $txt['product_list'] = '';
        }
        $txt['main_body'] = quick_tpl($tpl, $txt);
        flush_tpl('adm');
    break;
}
