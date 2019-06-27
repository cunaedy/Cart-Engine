<?php
require './../includes/admin_init.php';

admin_check(4);

// sort def
$sort_def = array('ia' => 'ID', 'ta' => 'Title', 'dd' => 'Entry Date', 'pa' => 'Price', 'sa' => 'Stock', 'ha' => 'View Hits', 'sl' => 'Sales');
$sort_sql = array('ia' => 'idx', 'ta' => 'title', 'dd' => 'list_date desc', 'pa' => 'price', 'sa' => 'stock', 'ha' => 'stat_hits desc', 'sl' => 'stat_purchased desc');

// search?
$cmd = get_param('cmd');
$keyword = get_param('keyword');
$cat_id = get_param('cat_id');
$start = date_param('start_date');
$end = date_param('end_date');
$items = get_param('items');
$sort = get_param('sort', 'ta');
$mode = get_param('mode');
$p = get_param('p', 1);

$sql_arr = array();
switch ($cmd) {
    case 'qEdit':
        $items = '';
        foreach ($_GET as $k => $v) {
            if (substr($k, 0, 7) == 'select_') {
                $kk = substr($k, 7);
                $items .= $kk.',';
            }
        }
        $items = substr($items, 0, -1);
        redir($config['site_url'].'/'.$config['admin_folder'].'/quickedit.php?items='.$items);
    break;


    case 'delAll':
        foreach ($_GET as $k => $v) {
            if (substr($k, 0, 7) == 'select_') {
                $kk = substr($k, 7);
                delete_item($kk);
            }
        }
        redir();
    break;


    default:
        if ($cmd == 'search') {
            if (!empty($keyword)) {
                $foo = array();
                $foo[] = create_where('title', $keyword);
                $foo[] = create_where('details', $keyword);
                $foo[] = create_where('idx', $keyword);
                $sql_arr[] = '('.implode(') OR (', $foo).')';
            }

            if (!empty($cat_id)) {
                $sql_arr[] = "(cat_id='$cat_id') OR (add_category LIKE '%,$cat_id,%')";
            }

            if (!empty($start)) {
                $sql_arr[] = "(list_date >= '$start') AND (list_date <= '$end')";
            }

            if (!empty($items)) {
                $sql_arr[] = "idx IN ($items)";
            }

            if ($mode == 'or') {
                $sql_str = '('.implode(') OR (', $sql_arr).')';
            } else {
                $sql_str = '('.implode(') AND (', $sql_arr).')';
            }

            if ($sql_str == '()') {
                $sql_str = '1=1';
            }
        } else {
            $sql_str = '1=1';
        }

        //
        $tpl = load_tpl('adm', 'product_list.tpl');
        $txt['block_list'] = '';

        // get list
        $result = sql_multipage($db_prefix.'products', '*', $sql_str, $sort_sql[$sort], $p);
        foreach ($result as $row) {
            // image
            if (file_exists('../public/products_thumbs/small_'.$row['idx'].'_1.jpg')) {
                $row['image_small'] = '../public/products_thumbs/small_'.$row['idx'].'_1.jpg';
            } else {
                $row['image_small'] = '../skins/_common/images/noimage.gif';
            }

            // cats
            $row['category'] = '<span title="'.$ce_cache['cat_structure'][$row['cat_id']].'" class="helpblack">'.$ce_cache['cat_name_def'][$row['cat_id']].'</span>';

            // others
            $row['price'] = num_format($row['price'], 0, 1);
            $row['summary'] = line_wrap(strip_tags($row['details']), 100);
            $row['list_date'] = convert_date($row['list_date']);

            $txt['block_list'] .= quick_tpl($tpl_block['list'], $row);
        }

        $txt['cat_id'] = $cat_id;
        $txt['keyword'] = $keyword;
        $txt['category_select'] = create_select_form('cat_id', $ce_cache['cat_structure'], $cat_id, '&nbsp;');
        $txt['start_date'] = date_form('start_date', date('Y'), 1, 1, $start ? $start : '2000-01-01');
        $txt['end_date'] = date_form('end_date', date('Y'), 1, 1, $end ? $end : $sql_today);
        $txt['mode_select'] = create_select_form('mode', array('and' => 'AND', 'or' => 'OR'), $mode);
        $txt['sort_select'] = create_select_form('sort', $sort_def, $sort);
        $txt['main_body'] = quick_tpl($tpl, $txt);
        flush_tpl('adm');
    break;
}
